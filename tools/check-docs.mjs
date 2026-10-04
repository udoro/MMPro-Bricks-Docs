#!/usr/bin/env node

// Asserts that the docs still describe the templates as they ship.
// Run after any template export, code change, or docs edit.
//
//   node tools/check-docs.mjs "<path to MMPRO BRICKS>"
//
// The templates are the source of truth. Checks:
//   1. Every attribute on a template element is documented.
//   2. Every data-* the code reads is documented, or listed as code-owned.
//   3. Every JS option key is documented.
//   4. Every CSS variable declared in the options code blocks is documented.
//   5. No em dashes, per the docs house style.
//   6. Every relative link and #anchor resolves.
//   7. Every attribute, data-* and CSS variable the skills files cite exists.

import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { join, dirname, resolve, relative } from 'node:path';
import { fileURLToPath } from 'node:url';

const SRC = process.argv[2];
if (!SRC) {
  console.error('usage: node tools/check-docs.mjs "<path to MMPRO BRICKS>"');
  process.exit(2);
}
const DOCS = resolve(dirname(fileURLToPath(import.meta.url)), '..');

let failures = 0;
const fail = (msg) => { console.error(`  FAIL  ${msg}`); failures++; };
const pass = (msg) => console.log(`  ok    ${msg}`);

/* sources */

function newest(re) {
  const hits = readdirSync(SRC).filter((f) => re.test(f)).sort();
  if (!hits.length) { console.error(`no template matching ${re} in ${SRC}`); process.exit(2); }
  return join(SRC, hits[hits.length - 1]);
}
const templates = {
  full: newest(/^template-mega-menu-pro-header-template-v\d+-\d{4}-\d{2}-\d{2}\.json$/),
  lite: newest(/^template-mega-menu-pro-header-template-v\d+-lite-\d{4}-\d{2}-\d{2}\.json$/),
  tabbed: newest(/^template-tabbed-navigation-v\d+-\d{4}-\d{2}-\d{2}\.json$/),
};
const elements = Object.fromEntries(Object.entries(templates).map(([k, f]) => {
  const j = JSON.parse(readFileSync(f, 'utf8'));
  return [k, j.header || j.content || []];
}));
const codeBlocks = Object.values(elements).flat().filter((e) => e.name === 'code').map((e) => ({
  label: e.label || e.id,
  css: e.settings?.cssCode || '',
  js: e.settings?.javascriptCode || '',
}));
// Add-on code: the RELEASE folder first, then the top level.
function addonFile(name) {
  const hit = [join(SRC, 'RELEASE', name), join(SRC, name)].find((p) => existsSync(p));
  if (!hit) { console.error(`no ${name} in ${SRC}/RELEASE or ${SRC}`); process.exit(2); }
  return readFileSync(hit, 'utf8');
}
const adaptiveJs = addonFile('adaptive-header-styling.js');

function markdownFiles(dir) {
  const out = [];
  for (const e of readdirSync(dir, { withFileTypes: true })) {
    if (e.name.startsWith('.') || e.name === 'node_modules' || e.name === 'tools') continue;
    const p = join(dir, e.name);
    if (e.isDirectory()) out.push(...markdownFiles(p));
    else if (e.name.endsWith('.md')) out.push(p);
  }
  return out;
}
const rel = (f) => relative(DOCS, f).replace(/\\/g, '/');
const everyFile = Object.fromEntries(markdownFiles(DOCS).map((f) => [rel(f), readFileSync(f, 'utf8')]));
// "Documented" means in the customer docs. The skills files are checked separately (7).
const docs = Object.fromEntries(Object.entries(everyFile).filter(([f]) => !f.startsWith('ai-connector/')));
const skills = Object.fromEntries(Object.entries(everyFile).filter(([f]) => f.startsWith('ai-connector/mmpro-bricks-skills/')));
const allDocs = Object.values(docs).join('\n');
const documented = (name) => allDocs.includes('`' + name + '`') || allDocs.includes('`' + name + '=');

/* 1. every element attribute documented */

// Present in the template but read by no code. Kept out of the docs until the
// developer confirms what they are for.
const UNEXPLAINED_ATTRS = new Set(['element-outline', 'data-tabbed-dropdown']);

const attrs = new Set();
for (const els of Object.values(elements)) {
  for (const e of els) for (const a of e.settings?._attributes || []) attrs.add(a.name);
}
const missingAttrs = [...attrs].filter((a) => !UNEXPLAINED_ATTRS.has(a) && !documented(a));
if (missingAttrs.length) fail(`template attribute(s) not documented: ${missingAttrs.join(', ')}`);
else pass(`all ${attrs.size - UNEXPLAINED_ATTRS.size} template attributes documented (not checked: ${[...UNEXPLAINED_ATTRS].join(', ')})`);

/* 2. every data-* the code reads is documented or code-owned */

// Set by the code itself, used only inside the builder, or third-party hooks.
const CODE_OWNED = /^data-(builder-mode|builder-modee|builder-window|breakinto-moved|format|id|original-logo-[a-z]+|text|toggle|x-overlay|tab-index|tabbed-nav-generated-label)$/;
const read = new Set();
for (const c of codeBlocks) for (const m of (c.css + c.js).matchAll(/\bdata-[a-z0-9-]+/g)) read.add(m[0]);
const adaptiveCode = adaptiveJs.replace(/\/\*[\s\S]*?\*\//g, '').replace(/\/\/[^\n]*/g, '');
for (const m of adaptiveCode.matchAll(/\bdata-[a-z0-9-]+/g)) read.add(m[0]);
const unaccounted = [...read].filter((a) => !CODE_OWNED.test(a) && !documented(a));
if (unaccounted.length) fail(`code reads data-* not in the docs: ${unaccounted.join(', ')}`);
else pass(`all ${read.size} data-* names the code reads are accounted for`);

/* 3. every JS option documented */

function objectKeys(js, name) {
  const m = js.match(new RegExp(`const\\s+${name}\\s*=\\s*\\{([\\s\\S]*?)\\n\\};?`));
  if (!m) return [];
  const body = m[1].replace(/\/\*[\s\S]*?\*\//g, '').replace(/\/\/[^\n]*/g, '');
  return [...body.matchAll(/(?:^|[,{\n])\s*([A-Za-z_][\w]*)\s*:/g)].map((k) => k[1]);
}
const options = new Set();
for (const c of codeBlocks) {
  for (const obj of ['MegaMenuCONFIG', 'CenteredLogoCONFIG', 'TabbedNavConfig', 'tabbedHeroConfig']) {
    if (!/options|tabbed/i.test(c.label)) continue;
    for (const k of objectKeys(c.js, obj)) options.add(k);
  }
}
for (const obj of ['HEADER_CONFIG']) for (const k of objectKeys(adaptiveJs, obj)) {
  if (k === 'headerElement' || k === 'wrapperSelectors') options.add(k);
}
const missingOpts = [...options].filter((k) => !documented(k));
if (!options.size) fail('found no JS options, so the parser no longer matches the code');
else if (missingOpts.length) fail(`JS option(s) not documented: ${missingOpts.join(', ')}`);
else pass(`all ${options.size} JS options documented`);

/* 4. every CSS variable in the options blocks documented */

// Declared but read by nothing: a typo for --chevron-clr in the button rule.
const UNUSED_VARS = new Set(['--chevron-color']);
const vars = new Set();
for (const c of codeBlocks) {
  if (!/options|tabbed/i.test(c.label)) continue;
  const css = c.css.replace(/\/\*[\s\S]*?\*\//g, '');
  // Tabbed Navigation's user-facing variables are its first :root block; the
  // rest of its CSS sets Mega Menu Pro variables, which are checked above.
  const scope = /tabbed/i.test(c.label) ? (css.match(/:root\s*\{([^}]*)\}/) || ['', ''])[1] : css;
  for (const m of scope.matchAll(/(?:^|[;{\s])(--[a-z0-9-]+)\s*:/g)) vars.add(m[1]);
}
const missingVars = [...vars].filter((v) => !UNUSED_VARS.has(v) && !documented(v));
if (missingVars.length) fail(`${missingVars.length} CSS variable(s) not documented: ${missingVars.slice(0, 12).join(', ')}`);
else pass(`all ${vars.size - UNUSED_VARS.size} CSS variables documented`);

/* 5. house style */

const dashed = Object.entries(everyFile).filter(([, s]) => s.includes('—')).map(([f]) => f);
if (dashed.length) fail(`em dash in: ${dashed.join(', ')}`);
else pass('no em dashes');

/* 6. links and anchors resolve */

const slug = (h) => h.trim().toLowerCase()
  .replace(/`/g, '').replace(/[^\p{L}\p{N}\s-]/gu, '').replace(/\s/g, '-');
function anchors(text) {
  const set = new Set();
  for (const m of text.matchAll(/^#{1,6}\s+(.+)$/gm)) set.add(slug(m[1]));
  return set;
}
let links = 0;
for (const [file, text] of Object.entries(everyFile)) {
  const clean = text.replace(/```[\s\S]*?```/g, '');
  for (const m of clean.matchAll(/\]\(([^)\s]+)\)/g)) {
    const target = m[1];
    if (/^(https?:|mailto:)/.test(target)) continue;
    links++;
    const [path, hash] = target.split('#');
    const abs = path ? resolve(DOCS, dirname(file), path) : resolve(DOCS, file);
    if (!existsSync(abs)) { fail(`${file}: broken link ${target}`); continue; }
    if (hash && abs.endsWith('.md') && !anchors(readFileSync(abs, 'utf8')).has(hash)) {
      fail(`${file}: no heading for #${hash} in ${relative(DOCS, abs)}`);
    }
  }
}
pass(`checked ${links} relative links`);

/* 7. names the skills cite exist */

// Everything the templates and their code contain.
const known = new Set([...attrs, ...read, ...options, ...vars, ...UNEXPLAINED_ATTRS, ...UNUSED_VARS]);
const adaptiveCss = addonFile('adaptive-header-styling.css');
for (const text of [...codeBlocks.map((c) => c.css), adaptiveJs, adaptiveCss]) {
  for (const m of text.matchAll(/--[a-z0-9-]+/g)) known.add(m[0]);
}
const skillText = Object.values(skills).join('\n');
const cited = new Set([...skillText.matchAll(/`((?:data-|--)[a-z0-9-]+|builder-preview-content-width|preview-[a-z]+)/g)].map((m) => m[1]));
// Written as a family in prose ("the --overlay-header-* vars"), not as one name.
const FAMILY = /^--(overlay-header|overlay-sidebar|menu-cta)-$/;
// Command-line flags in the verification steps, not CSS variables.
const CLI_FLAGS = new Set(['--headless']);
const unknown = [...cited].filter((n) => !known.has(n) && !FAMILY.test(n) && !CLI_FLAGS.has(n));
if (!Object.keys(skills).length) fail('no skills files found');
else if (unknown.length) fail(`skills cite name(s) not in the templates: ${unknown.join(', ')}`);
else pass(`all ${cited.size} names cited in the skills exist in the templates`);

console.log(failures ? `\n${failures} check(s) failed` : '\nall checks passed');
process.exit(failures ? 1 : 0);
