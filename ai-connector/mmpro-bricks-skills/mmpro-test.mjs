// Headless Chrome helper for checking a Mega Menu Pro header on a published page.
// Needs Node 22+ and Chrome. Nothing to install. Set CHROME_PATH if Chrome is not found.
//
//   import { open, lines } from './mmpro-test.mjs';   // path relative to your script
//   const b = await open({ url: 'https://example.com/', width: 390, height: 844, mobile: true });
//   try {
//     await b.tap('.dwc-nest-toggle--open'); await b.settle();
//     console.log(await b.box('.dwc-nav-wrapper'), b.errors);
//     await b.shot('menu.png');
//   } finally { await b.close(); }
//
// Measure text lines in a screenshot (PNG):
//   node mmpro-test.mjs lines <png> <x0> <x1> <y0> <y1>

import { spawn } from 'node:child_process';
import { mkdtempSync, rmSync, readFileSync, writeFileSync, existsSync } from 'node:fs';
import { tmpdir, platform } from 'node:os';
import { join, resolve } from 'node:path';
import { inflateSync } from 'node:zlib';
import { fileURLToPath } from 'node:url';

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

const CHROME = {
  win32: [
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    `${process.env.LOCALAPPDATA}/Google/Chrome/Application/chrome.exe`,
  ],
  darwin: ['/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', '/Applications/Chromium.app/Contents/MacOS/Chromium'],
  linux: ['/usr/bin/google-chrome', '/usr/bin/google-chrome-stable', '/usr/bin/chromium', '/usr/bin/chromium-browser', '/snap/bin/chromium'],
};

function chromePath() {
  const hit = [process.env.CHROME_PATH, ...(CHROME[platform()] || [])].filter(Boolean).find((p) => existsSync(p));
  if (!hit) throw new Error('Chrome not found. Set CHROME_PATH to the Chrome executable.');
  return hit;
}

/**
 * Opens `url` in a fresh headless Chrome with its own temporary profile.
 * adminBar: adds a copy of the WordPress admin bar, as logged-in users see it.
 */
export async function open({ url, width = 1440, height = 900, dpr = 1, mobile = width < 768, adminBar = false }) {
  if (!url) throw new Error('open() needs a url');
  const profile = mkdtempSync(join(tmpdir(), 'mmpro-test-'));
  const chrome = spawn(chromePath(), [
    '--headless=new', '--remote-debugging-port=0', `--user-data-dir=${profile}`,
    '--no-first-run', '--no-default-browser-check', '--disable-gpu', '--hide-scrollbars', 'about:blank',
  ], { stdio: 'ignore' });
  const exited = new Promise((r) => chrome.once('exit', r));

  let ws;
  // Asks Chrome to quit (so no child process keeps the profile open), then deletes the profile.
  const close = async () => {
    try { if (ws?.readyState === 1) ws.send(JSON.stringify({ id: 0, method: 'Browser.close' })); } catch {}
    await Promise.race([exited, sleep(5000)]);
    try { ws?.close(); } catch {}
    if (chrome.exitCode === null) { chrome.kill(); await Promise.race([exited, sleep(2000)]); }
    for (let i = 0; i < 20; i++) {
      try { rmSync(profile, { recursive: true, force: true }); break; } catch { await sleep(250); }
    }
  };

  try {
    // Chrome picks a free port and writes it to DevToolsActivePort.
    let port;
    for (let i = 0; i < 100 && !port; i++) {
      await sleep(150);
      try { port = readFileSync(join(profile, 'DevToolsActivePort'), 'utf8').split('\n')[0].trim(); } catch {}
    }
    if (!port) throw new Error('Chrome did not start');
    let target;
    for (let i = 0; i < 50 && !target; i++) {
      try { target = (await (await fetch(`http://127.0.0.1:${port}/json/list`)).json()).find((t) => t.type === 'page'); } catch {}
      if (!target) await sleep(100);
    }
    ws = new WebSocket(target.webSocketDebuggerUrl);
    await new Promise((r, j) => { ws.onopen = r; ws.onerror = () => j(new Error('could not connect to Chrome')); });
  } catch (e) { await close(); throw e; }

  let id = 0;
  const pending = {}, errors = [], sheets = {};
  ws.onmessage = (m) => {
    const d = JSON.parse(m.data);
    if (d.id && pending[d.id]) { pending[d.id](d); delete pending[d.id]; }
    // A new page in the main frame: keep only its errors.
    else if (d.method === 'Page.frameNavigated' && !d.params.frame.parentId) errors.length = 0;
    else if (d.method === 'Runtime.exceptionThrown') {
      const x = d.params.exceptionDetails;
      errors.push((x.exception?.description || x.text || '').split('\n')[0].slice(0, 200));
    } else if (d.method === 'CSS.styleSheetAdded') sheets[d.params.header.styleSheetId] = d.params.header;
  };
  const cmd = (method, params = {}) => new Promise((r, j) => {
    const i = ++id;
    pending[i] = (d) => (d.error ? j(new Error(`${method}: ${d.error.message}`)) : r(d.result));
    ws.send(JSON.stringify({ id: i, method, params }));
  });
  const ev = async (expression) => {
    const r = await cmd('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
    if (r.exceptionDetails) throw new Error('in page: ' + (r.exceptionDetails.exception?.description || r.exceptionDetails.text).split('\n')[0]);
    return r.result?.value;
  };
  const fn = (f, ...args) => ev(`(${f.toString()})(${args.map((a) => JSON.stringify(a)).join(',')})`);
  const center = async (sel) => {
    const c = await fn((s) => {
      const e = document.querySelector(s);
      if (!e) return null;
      const r = e.getBoundingClientRect();
      return { x: r.left + r.width / 2, y: r.top + r.height / 2 };
    }, sel);
    if (!c) throw new Error(`no element matches ${sel}`);
    return c;
  };

  const b = {
    errors,
    sleep,
    /** Raw DevTools Protocol command. */
    cmd,
    /** Runs a function in the page and returns its (JSON) result. */
    fn,
    /** Draft CSS, added at the end of <head>, where Bricks puts class CSS. Only the published page proves it. */
    css: (text) => fn((t) => {
      let s = document.getElementById('mmpro-test-draft');
      if (!s) { s = document.createElement('style'); s.id = 'mmpro-test-draft'; document.head.appendChild(s); }
      s.textContent = t;
    }, text),
    /** Viewport box of the first match, rounded, or null. */
    box: (sel) => fn((s) => {
      const e = document.querySelector(s);
      if (!e) return null;
      const r = e.getBoundingClientRect();
      return { x: Math.round(r.left), y: Math.round(r.top), w: Math.round(r.width), h: Math.round(r.height) };
    }, sel),
    move: (x, y) => cmd('Input.dispatchMouseEvent', { type: 'mouseMoved', x, y }),
    /** Moves the mouse onto the top-level menu item with this text (desktop dropdowns open on hover). */
    async hover(text) {
      const c = await fn((t) => {
        for (const li of document.querySelectorAll('.dwc-nest-menu .brx-nav-nested-items > li')) {
          const el = li.querySelector(':scope > .brx-submenu-toggle, :scope > a');
          const label = [el?.textContent, el?.querySelector('[aria-label]')?.getAttribute('aria-label')].map((s) => (s || '').trim());
          if (el && label.includes(t)) { const r = el.getBoundingClientRect(); return { x: r.left + r.width / 2, y: r.top + r.height / 2 }; }
        }
        return null;
      }, text);
      if (!c) throw new Error(`no top-level menu item with the text "${text}"`);
      await b.move(c.x, c.y);
    },
    async click(sel) {
      const { x, y } = await center(sel);
      await cmd('Input.dispatchMouseEvent', { type: 'mouseMoved', x, y });
      await cmd('Input.dispatchMouseEvent', { type: 'mousePressed', x, y, button: 'left', clickCount: 1 });
      await cmd('Input.dispatchMouseEvent', { type: 'mouseReleased', x, y, button: 'left', clickCount: 1 });
    },
    async tap(sel) {
      if (!mobile) return b.click(sel);
      const { x, y } = await center(sel);
      await cmd('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x, y }] });
      await cmd('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
    },
    scroll: (y) => fn((top) => new Promise((r) => { window.scrollTo(0, top); requestAnimationFrame(() => requestAnimationFrame(r)); }), y),
    /** Waits until no transition or animation is running (endless ones ignored), at most `max` ms. */
    async settle(max = 3000) {
      const end = Date.now() + max;
      let quiet = 0;
      await sleep(100);
      while (quiet < 3 && Date.now() < end) {
        const running = await fn(() => document.getAnimations()
          .filter((a) => a.playState === 'running' && a.effect?.getComputedTiming().iterations !== Infinity).length);
        quiet = running ? 0 : quiet + 1;
        await sleep(50);
      }
    },
    /**
     * Which rules set `prop` on the first match (pseudo: 'before' or 'after'), in cascade order,
     * with the winner marked, plus the computed value.
     */
    async why(sel, prop, pseudo) {
      const ps = pseudo ? pseudo.replace(/^:+/, '') : null;
      await cmd('DOM.enable'); await cmd('CSS.enable');
      const { root } = await cmd('DOM.getDocument', { depth: 0 });
      const { nodeId } = await cmd('DOM.querySelector', { nodeId: root.nodeId, selector: sel });
      if (!nodeId) throw new Error(`no element matches ${sel}`);
      const m = await cmd('CSS.getMatchedStylesForNode', { nodeId });
      const matches = ps ? (m.pseudoElements || []).find((p) => p.pseudoType === ps)?.matches || [] : m.matchedCSSRules || [];
      // A file name, or the <style> element: its id, else the element it sits in.
      const source = async (r) => {
        if (r.origin !== 'regular') return r.origin;
        const h = sheets[r.styleSheetId];
        if (!h) return '?';
        if (!h.isInline || !h.ownerNode) return h.sourceURL.split('/').pop().split('?')[0] || 'inline';
        const { object } = await cmd('DOM.resolveNode', { backendNodeId: h.ownerNode });
        const { result } = await cmd('Runtime.callFunctionOn', {
          objectId: object.objectId, returnByValue: true,
          functionDeclaration: `function () {
            if (this.id) return '<style id="' + this.id + '">';
            const p = this.parentElement;
            return '<style> in ' + p.tagName.toLowerCase() + (p.id ? '#' + p.id : '') + [...p.classList].slice(0, 2).map((c) => '.' + c).join('');
          }`,
        });
        return result.value;
      };
      const rules = [];
      for (const { rule } of matches) {
        for (const p of rule.style.cssProperties) {
          if (p.name !== prop || p.disabled) continue;
          rules.push({ selector: rule.selectorList.text, value: p.value, important: !!p.important, valid: p.parsedOk !== false, source: await source(rule) });
        }
      }
      if (!ps) for (const p of m.inlineStyle?.cssProperties || []) {
        if (p.name === prop && !p.disabled) rules.push({ selector: 'style=""', value: p.value, important: !!p.important, valid: p.parsedOk !== false, source: 'inline style attribute' });
      }
      const valid = rules.filter((r) => r.valid);
      const winner = valid.filter((r) => r.important).pop() || valid.pop();
      if (winner) winner.wins = true;
      const computed = await fn((s, p, x) => getComputedStyle(document.querySelector(s), x ? '::' + x : null).getPropertyValue(p), sel, prop, ps);
      return { computed, rules };
    },
    /** PNG screenshot. `clip` is in viewport pixels ({ x, y, width, height }); it follows the scroll position. */
    async shot(file, clip) {
      const sy = clip ? await fn(() => window.scrollY) : 0;
      const r = await cmd('Page.captureScreenshot', { format: 'png', ...(clip ? { clip: { ...clip, y: clip.y + sy, scale: 1 } } : {}) });
      writeFileSync(file, Buffer.from(r.data, 'base64'));
      return file;
    },
    close,
  };

  try {
    await cmd('Page.enable');
    await cmd('Runtime.enable');
    await cmd('Emulation.setDeviceMetricsOverride', { width, height, deviceScaleFactor: dpr, mobile });
    if (mobile) await cmd('Emulation.setTouchEmulationEnabled', { enabled: true, maxTouchPoints: 5 });
    const u = new URL(url);
    u.searchParams.set('nocache', String(Date.now()));
    const nav = await cmd('Page.navigate', { url: u.href });
    if (nav.errorText) throw new Error(`could not load ${u.href}: ${nav.errorText}`);
    // Wait for the real page: some hosts show a browser check first, then reload.
    let page = null;
    for (const end = Date.now() + 30000; Date.now() < end;) {
      page = await fn(() => ({
        ready: document.readyState === 'complete',
        is404: document.body?.classList.contains('error404'),
        header: !!document.querySelector('.dwc-nest-header'),
        title: document.title,
      })).catch(() => null); // mid-navigation
      if (page?.ready && (page.header || page.is404)) break;
      await sleep(250);
    }
    if (page?.is404) throw new Error('The page is a 404. Check the URL. In query strings use nocache or long keys: WordPress reserves short ones such as m, w, p and s.');
    if (!page?.header) throw new Error(`No Mega Menu Pro header (.dwc-nest-header) after 30s. Page title: "${page?.title}". See the entry file, "When the page disagrees with the builder".`);
    await ev('document.fonts.ready.then(() => true)');
    if (adminBar) {
      await fn((h) => {
        const s = document.createElement('style');
        s.id = 'mmpro-test-admin-bar';
        s.textContent = `html{--wp-admin--admin-bar--height:${h}px;margin-top:${h}px !important}`
          + `#wpadminbar{position:fixed;top:0;left:0;right:0;height:${h}px;z-index:99999;background:#1d2327}`
          + '@media screen and (max-width:600px){#wpadminbar{position:absolute}}';
        document.head.appendChild(s);
        const bar = document.createElement('div');
        bar.id = 'wpadminbar';
        document.body.prepend(bar);
        document.body.classList.add('admin-bar');
        window.dispatchEvent(new Event('resize'));
      }, width <= 782 ? 46 : 32);
    }
    await b.settle();
  } catch (e) { await close(); throw e; }
  return b;
}

/** Decodes an 8-bit, non-interlaced grey, RGB or RGBA PNG. */
function readPng(file) {
  const buf = readFileSync(file);
  if (buf.length < 8 || buf.readUInt32BE(0) !== 0x89504e47) throw new Error(`${file} is not a PNG. Save the screenshot as PNG.`);
  let pos = 8, width, height, depth, type, interlace;
  const idat = [];
  while (pos < buf.length) {
    const len = buf.readUInt32BE(pos);
    const kind = buf.toString('ascii', pos + 4, pos + 8);
    const data = buf.subarray(pos + 8, pos + 8 + len);
    if (kind === 'IHDR') { width = data.readUInt32BE(0); height = data.readUInt32BE(4); depth = data[8]; type = data[9]; interlace = data[12]; }
    else if (kind === 'IDAT') idat.push(data);
    else if (kind === 'IEND') break;
    pos += 12 + len;
  }
  const ch = { 0: 1, 2: 3, 4: 2, 6: 4 }[type];
  if (depth !== 8 || !ch || interlace) throw new Error(`${file}: only 8-bit grey, RGB or RGBA PNGs without interlacing. Re-save it as a plain PNG.`);
  const raw = inflateSync(Buffer.concat(idat));
  const stride = width * ch;
  const px = Buffer.alloc(height * stride);
  for (let y = 0; y < height; y++) {
    const filter = raw[y * (stride + 1)];
    const line = raw.subarray(y * (stride + 1) + 1, (y + 1) * (stride + 1));
    const out = px.subarray(y * stride, (y + 1) * stride);
    const prev = y ? px.subarray((y - 1) * stride, y * stride) : null;
    for (let i = 0; i < stride; i++) {
      const a = i >= ch ? out[i - ch] : 0, up = prev ? prev[i] : 0, c = prev && i >= ch ? prev[i - ch] : 0;
      let v = line[i];
      if (filter === 1) v += a;
      else if (filter === 2) v += up;
      else if (filter === 3) v += (a + up) >> 1;
      else if (filter === 4) {
        const p = a + up - c, pa = Math.abs(p - a), pb = Math.abs(p - up), pc = Math.abs(p - c);
        v += pa <= pb && pa <= pc ? a : pb <= pc ? up : c;
      }
      out[i] = v & 255;
    }
  }
  return { width, height, ch, px };
}

/**
 * Text lines in a screenshot: the rows with ink (R+G+B below `ink`) inside the box, grouped into
 * runs [top, bottom], plus the leftmost ink column. Coordinates are image pixels.
 */
export function lines(png, { x: [x0, x1], y: [y0, y1] }, ink = 560) {
  const { width, height, ch, px } = readPng(png);
  x1 = Math.min(x1, width - 1); y1 = Math.min(y1, height - 1);
  const sum = (x, y) => { const i = (y * width + x) * ch; return ch < 3 ? px[i] * 3 : px[i] + px[i + 1] + px[i + 2]; };
  const runs = [];
  let start = -1, left = null;
  for (let y = y0; y <= y1; y++) {
    let hit = false;
    for (let x = x0; x <= x1; x++) if (sum(x, y) < ink) { hit = true; if (left === null || x < left) left = x; }
    if (hit && start < 0) start = y;
    if (!hit && start >= 0) { runs.push([start, y - 1]); start = -1; }
  }
  if (start >= 0) runs.push([start, y1]);
  return { left, lines: runs };
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
  const [command, file, ...n] = process.argv.slice(2);
  if (command !== 'lines' || !file || n.length !== 4) {
    console.log('usage: node mmpro-test.mjs lines <png> <x0> <x1> <y0> <y1>');
    process.exit(command ? 1 : 0);
  }
  const [x0, x1, y0, y1] = n.map(Number);
  const r = lines(file, { x: [x0, x1], y: [y0, y1] });
  console.log(`left ink ${r.left ?? 'none'} | lines ${r.lines.map(([a, z]) => `${a}-${z}`).join(' ')}`);
}
