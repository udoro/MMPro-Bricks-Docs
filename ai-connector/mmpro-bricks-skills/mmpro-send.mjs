// Sends a Mega Menu Pro template file to the site, through the MCP connection the agent already uses.
// The file is too large for a tool call. Needs Node 22+. Nothing to install.
//
//   node mmpro-send.mjs import "<template.json>" [--title "..."] [--logo <attachmentId>] [--dry-run] [--server <name>]
//   node mmpro-send.mjs repair <postId> "<template.json>" [--dry-run] [--server <name>]
//
// Connection: MMPRO_SITE_URL + MMPRO_USER + MMPRO_APP_PASSWORD when set; otherwise the agent's MCP
// settings (.mcp.json, ~/.claude.json, .cursor/mcp.json). The password is sent only to that site's
// URL and never printed.

import { readFileSync, existsSync } from 'node:fs';
import { homedir } from 'node:os';
import { join, dirname, resolve } from 'node:path';

const USAGE = `usage:
  node mmpro-send.mjs import "<template.json>" [--title "..."] [--logo <attachmentId>] [--dry-run] [--server <name>]
  node mmpro-send.mjs repair <postId> "<template.json>" [--dry-run] [--server <name>]`;

function fail(message, code = 1) {
  console.error(message);
  process.exit(code);
}

function parseArgs(argv) {
  const out = { positional: [], flags: {} };
  for (let i = 0; i < argv.length; i++) {
    const a = argv[i];
    if (a === '--dry-run') out.flags.dryRun = true;
    else if (a.startsWith('--')) out.flags[a.slice(2)] = argv[++i];
    else out.positional.push(a);
  }
  return out;
}

const readJson = (file) => { try { return JSON.parse(readFileSync(file, 'utf8')); } catch { return null; } };
const expand = (s) => String(s).replace(/\$\{(\w+)\}/g, (m, k) => process.env[k] ?? m);
const host = (url) => { try { return new URL(url).host; } catch { return url; } };

/** MCP servers that point at a WordPress site and send an Authorization header. */
function findServers() {
  const found = [];
  const add = (source, servers) => {
    for (const [name, s] of Object.entries(servers || {})) {
      const url = s && (s.url || s.serverUrl);
      const auth = s && s.headers && expand(s.headers.Authorization ?? s.headers.authorization ?? '');
      if (url && /\/wp-json\//.test(url) && auth && !/\$\{/.test(auth)) found.push({ name, source, url: expand(url), auth });
    }
  };
  for (let dir = process.cwd(); ; dir = dirname(dir)) {
    add('.mcp.json', readJson(join(dir, '.mcp.json'))?.mcpServers);
    add('.cursor/mcp.json', readJson(join(dir, '.cursor', 'mcp.json'))?.mcpServers);
    if (dirname(dir) === dir) break;
  }
  const claude = readJson(join(homedir(), '.claude.json'));
  if (claude) {
    const norm = (p) => resolve(p).replace(/\\/g, '/').toLowerCase();
    const cwd = norm(process.cwd());
    for (const [path, project] of Object.entries(claude.projects || {})) {
      const p = norm(path);
      if (cwd === p || cwd.startsWith(p + '/')) add('~/.claude.json', project.mcpServers);
    }
    add('~/.claude.json', claude.mcpServers);
  }
  add('~/.cursor/mcp.json', readJson(join(homedir(), '.cursor', 'mcp.json'))?.mcpServers);
  const seen = new Set();
  return found.filter((s) => (seen.has(s.name + s.url) ? false : seen.add(s.name + s.url)));
}

function connection(serverName) {
  const { MMPRO_SITE_URL: site, MMPRO_USER: user, MMPRO_APP_PASSWORD: pass, MMPRO_MCP_URL: mcpUrl } = process.env;
  if (site && user && pass) {
    return {
      name: 'MMPRO_SITE_URL',
      url: mcpUrl || site.replace(/\/+$/, '') + '/wp-json/mcp/mcp-adapter-default-server',
      auth: 'Basic ' + Buffer.from(`${user}:${pass.replace(/\s+/g, '')}`).toString('base64'),
    };
  }
  let servers = findServers();
  if (serverName) servers = servers.filter((s) => s.name === serverName);
  const urls = [...new Set(servers.map((s) => s.url))];
  if (!servers.length) {
    fail(`No WordPress MCP connection found${serverName ? ` named "${serverName}"` : ''} in .mcp.json, ~/.claude.json or .cursor/mcp.json.
Ask the user for the site URL, their username and an application password, then set
MMPRO_SITE_URL, MMPRO_USER and MMPRO_APP_PASSWORD and run this again.`, 2);
  }
  if (urls.length > 1) {
    fail('More than one WordPress MCP connection. Pick one with --server <name>:\n' + servers.map((s) => `  ${s.name}  (${host(s.url)}, from ${s.source})`).join('\n'), 2);
  }
  return servers[0];
}

async function callAbility(conn, abilityName, parameters) {
  let session = null;
  let id = 0;
  const rpc = async (method, params, notify = false) => {
    const headers = { 'Content-Type': 'application/json', Accept: 'application/json, text/event-stream', Authorization: conn.auth };
    if (session) headers['Mcp-Session-Id'] = session;
    if (method !== 'initialize') headers['MCP-Protocol-Version'] = '2025-11-25';
    const body = notify ? { jsonrpc: '2.0', method, params } : { jsonrpc: '2.0', id: ++id, method, params };
    const res = await fetch(conn.url, { method: 'POST', headers, body: JSON.stringify(body) });
    session = res.headers.get('mcp-session-id') || session;
    const text = await res.text();
    if (res.status === 401 || res.status === 403) fail(`The site refused the login (HTTP ${res.status}). Check the application password.`);
    if (notify) return null;
    const line = text.match(/^data: (.*)$/m);
    try { return JSON.parse(line ? line[1] : text); } catch { fail(`Unexpected answer from ${host(conn.url)} (HTTP ${res.status}): ${text.slice(0, 300)}`); }
  };
  await rpc('initialize', { protocolVersion: '2025-11-25', capabilities: {}, clientInfo: { name: 'mmpro-send', version: '1' } });
  await rpc('notifications/initialized', {}, true);
  const r = await rpc('tools/call', { name: 'mcp-adapter-execute-ability', arguments: { ability_name: abilityName, parameters } });
  const text = (r.result?.content || []).map((c) => c.text || '').join('');
  if (r.error || r.result?.isError) return { ok: false, error: text || JSON.stringify(r.error) };
  try {
    const j = JSON.parse(text);
    return j.success === false ? { ok: false, error: text } : { ok: true, data: j.data ?? j };
  } catch {
    return { ok: false, error: text };
  }
}

const { positional, flags } = parseArgs(process.argv.slice(2));
const [command, ...rest] = positional;
let ability, parameters, file;
if (command === 'import' && rest.length === 1) {
  file = rest[0];
  ability = 'mmpro/import-header';
  parameters = {};
  if (flags.title) parameters.title = flags.title;
  if (flags.logo) parameters.logoAttachmentId = Number(flags.logo);
} else if (command === 'repair' && rest.length === 2 && /^\d+$/.test(rest[0])) {
  file = rest[1];
  ability = 'mmpro/repair-code-blocks';
  parameters = { postId: Number(rest[0]) };
} else {
  fail(USAGE, command ? 1 : 0);
}
if (!existsSync(file)) fail(`File not found: ${file}`);
const template = readJson(file);
if (!template || typeof template !== 'object') fail(`${file} is not a JSON template file.`);
parameters.template = template;
if (flags.dryRun) parameters.dryRun = true;

const conn = connection(flags.server);
console.error(`Sending ${file} to ${host(conn.url)} (${conn.name}) with ${ability}${flags.dryRun ? ', dry run' : ''}...`);
const result = await callAbility(conn, ability, parameters);
if (!result.ok) fail(`${ability} failed: ${result.error}`);
console.log(JSON.stringify(result.data, null, 2));
