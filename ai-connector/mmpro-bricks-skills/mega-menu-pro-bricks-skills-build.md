---
icon: sparkles
---

# AI Skills Build Reference

## START HERE

The entry file sent you here because you are on the **build path**. Read this file in full, then
work through the steps in order.

### 0. Read this file first

1. Read every line before your first Bricks write. If a read returns a truncation notice, continue
   with `offset`/`limit` until you reach the last line.
2. State in chat: `"Skill file read: lines 1-[N] of [N], complete."` with the real line count.

### 1. Before you start

**Session type.** Search upward from this file's folder for `MMPRO BRICKS/mmpro-bricks-dev-context.md`,
checking each ancestor in turn. Found: **developer session**, read it silently. Not found: **user
session**.

**User session only.** Look for `mmpro-bricks-user-context.md` in this file's folder. If it exists,
read it silently. If not, create it with the structure below and tell the user you created it.

```md
# MMPro Bricks User Context

## Preferences


## Saved Layouts


## Session Notes

```

At the end of a user session, propose additions to it (the entry file's "Ask before you write"
applies). A saved preference in the active context file wins over a default in this file. When you
apply one, tell the user.

**Working directory.** Make a working subdirectory for this task and keep every scratch file in it
(page snapshots, scripts). Delete it at the end, except any backup you were told to keep. Use `node`
for scripting and parsing. Never `python` or `jq`.

**Timestamp.** Run `date +%s` before you start work and save the value in your working directory.

### 2. Connect and find the header

Follow "Is the site connected" and "Find the header" in the entry file. Record the IDs in its table.

**No header:** if no header template has a root `section` labelled `Header Pro`, ask the user for the
template file they downloaded (Full or Lite). Find their logo with `bricks/find-media` and ask them
to confirm it. Then import it:
`node mmpro-bricks-skills/mmpro-send.mjs import "<path to the .json file>" --logo <attachment ID>`
If it reports `code_execution_off`, tell the user to turn on **Code execution** in Bricks > Settings >
Custom code for their role, then run it again. Never build a header from Bricks elements.

### 3. Scope and confirmation gate

Before any write that changes structure or look, ask the user in **one** question batch:

* **(a) Adjust what is there** (keep the menu items, change look or behavior), or **(b) rebuild**
  (remove items and build new ones).
* **Class names.** If you will create a new global class family for mega menu content, propose two
  or three base names and let the user pick or give their own. Never invent and apply one.
* **Unclear phrases.** List every phrase in the brief with more than one technical reading (a vague
  width, an unstated open/closed state, two requirements that may be one setting) and resolve them in
  the same batch.
* **Screenshots.** If the user gives design screenshots, ask the browser zoom and window width each
  was taken at.

**Before option (b):** call `bricks/list-revisions` for the header template, note the latest
revision, and tell the user that is the restore point. Do not remove anything until the user
confirms.

To undo, tell the user to restore from the builder's Revisions panel and save. Never call
`bricks/restore-revision` on an MMPro header: it strips backslashes from the code blocks.

### 4. Order of tools

Use the first tier that can do the job. Name the tier in chat before each write.

1. **Attribute** on Header Pro, Nav (Nestable), a Dropdown or a mega menu Content element. Reference
   sections 2 to 4.
2. **CSS variable** in MENU Styles / Options. Reference section 5.
3. **JS option** in MENU Styles / Options. Reference section 6.
4. **Bricks element settings or global class styles**, for the mega menu content you build.
5. **Custom CSS** on an element or class, only when nothing above covers it. Say in chat which
   variable or attribute you checked and why it does not cover the request.

***

## 5. Operations

Every write goes through the MMPro AI Abilities plugin (`mmpro/*`), called with
`mcp-adapter-execute-ability`. Never use Bricks' element-write abilities (`update-element`,
`batch-update-elements`, `add-element`, `remove-element`, `set-page-elements`) on an MMPro header:
they reject it with `bricks_conflict_missing_navigation_scaffold`. Get an ability's exact input
with `mcp-adapter-get-ability-info`.

### Read the header

`mmpro/get-header` with no input finds the header; pass `postId` if it lists several. It returns the
element IDs, both attribute sets, the menu tree (mega menus carry `contentId`), the JS options and a
`digest`. Add `include: ["cssVariables"]` only when you need variable values.

Pass the latest `digest` as `expectedDigest` on every write. `mmpro_stale` means the header changed:
read it again.

### Dry run first

Every write takes `dryRun: true`. Use it before the first write of each kind in a session and before
any write that adds or removes elements.

### Change an attribute

`mmpro/set-attributes`: `postId`, `elementId`, `attributes: { name: value }`. A string sets it, `""`
turns it off, `null` removes it. Turn off presence-only attributes (`data-breakout-link`,
`data-breakin`, `data-breakinto`, `data-is-button`, `data-is-icon`) with `null`.

### Change a CSS variable

`mmpro/set-css-variables`: `postId`, `variables: { "--name": "value" }`, `rule`:
`":root"` (default, every screen, existing variables only), `"html.dwc-mobile"` (mobile menu only) or
`".brx-sticky.scrolling"` (sticky only). The last two add a missing variable. Give zero a unit.

### Change a JS option

`mmpro/set-js-options`: `postId`, `object` (`MegaMenuCONFIG` or `CenteredLogoCONFIG`),
`options: { name: value }`. Existing options only. Never change `minWidth` alone (see Change the
breakpoint).

### Change a menu item

`mmpro/set-menu-item`: `postId`, `elementId`, any of `text`, `url`, `newTab`. A Dropdown takes `text`
only.

### Change an element's settings

`mmpro/set-element-settings`: `postId`, `elementId`, `settings: { key: value }` for an element inside
Nav items, as in the builder panel. `null` removes a key; keys not named stay. Use it for an icon,
classes (`_cssGlobalClasses`), a tag or layout settings, instead of removing and re-adding the
element. Refused: code-running keys, `_attributes` (use `mmpro/set-attributes`), `_hidden`,
`megaMenu`. A Content Inner keeps tag `li`.

### Add a menu item, dropdown or mega menu

`mmpro/duplicate-element` copies an element of the same kind with new IDs, `position` `"after"`
(default) or `"before"`, optional `text`/`url` for the copy. Copy a top-level `text-link`, a multilevel
`dropdown`, or a mega menu `dropdown`. If the Nav uses `data-last-item-is-button`, ask where a new item
goes before adding it last.

### Remove a menu element

`mmpro/remove-element` removes the element and everything inside it. Before removing more than one,
list them to the user and wait for agreement. A dropdown's Content can't be removed on its own:
remove the Dropdown, or what's inside its Content.

### Build mega menu content

1. Agree the base class name (gate, step 3). Create global classes with Bricks' own class abilities;
   they don't touch the header, so they work. Pass their IDs in `settings._cssGlobalClasses`.
   * `bricks/list-global-classes` returns 25 per page: pass `perPage: 100`.
   * `bricks/batch-create-global-classes` needs the list's `ownership` as `expectedOwnership`, and
     keeps 6-character IDs you give it, so you can use them in the same plan.
   * `bricks/update-global-class` needs the class's `itemOwnership` and the list's `lockOwnership`
     from a fresh read before every update.
2. Add **one** item with `mmpro/add-elements` (`parentId` = the mega menu's Content Inner,
   `elements: [{ name, label?, settings?, children? }]`). Check it on the published page.
3. Then add the rest.

All content goes inside Content Inner. If a mega menu has none, add one first: an element with
`tag: "li"` inside Content. `mmpro/add-elements` into a mega menu's Content only accepts a Content
Inner (`tag: "li"`). Layout goes on Content Inner or your own blocks, never on Content.
Code-running settings are refused.

Check the root font size before using `rem`: `getComputedStyle(document.documentElement).fontSize`.
Bricks sites often set it to 10px. Prefer px, or MMPro variables such as
`calc(var(--menu-item-font-size) * 0.93)`, which also scale up in the mobile menu.
Include the layout below the breakpoint in the same pass. If `get-header` returns
`cssLoading: "file"`, call `bricks/regenerate-css-files` after adding styled elements.

### Match a design from screenshots

1. Test at viewport width = screenshot width ÷ zoom, with `dpr` = zoom, so your screenshots have the
   same scale. This holds when the screenshot shows the full window width.
2. Screenshots are often cropped at the top. Align on the nav text row before comparing.
3. Compare text-line positions measured from pixels in both images (`lines` in `mmpro-test.mjs`).
   Never judge spacing by eye.
4. If the design's font is not on the site, use a free look-alike. Match its size by measured text
   width, not the nominal size.
5. Compare every state the screenshots show, at each size they show.

### Change the breakpoint

`mmpro/set-breakpoint`: `postId`, `desktopMinWidth` (the first desktop width; mobile is one pixel
less). It makes the documented change and nothing else: `minWidth`, and the widths inside the
`@media` rules of MEDIA QUERY and MEGA MENU Codes. `get-header` returns the current `breakpoint`.
If Tabbed Navigation is on the site, change its breakpoint too (reference section 8).

Never raise the breakpoint to show the mobile menu on desktop. Set `data-offcanvas` to `true`
instead (Full only).

### Add a header row

* A header row (a top bar, a second row) is a Bricks `block` directly inside Header Pro, above or
  below the main container: `mmpro/add-elements` with Header Pro as `parentId`, `position: 0` for the
  top. Never a `container`: Mega Menu Pro moves `--header-inline-padding` from Header Pro onto these
  blocks only, so each row's background spans the full width.
* `mmpro/get-header` lists the rows. Everything inside a row can be changed like the menu.
* Once a row exists, Header Pro has no side padding. Give the main container
  (`.dwc-nest-header__container`) its width and side spacing in your CSS, and give the row's content
  the same width, so the rows line up.
* The menu row's background is `--header-bg`; a texture or image for it goes on `.dwc-nest-header`
  in your CSS. Rows cover it with their own background.
* Wrapping the main container in a block (it then takes `--header-bg`) is a builder step for the
  user: the plugin doesn't move the main container.

### Import or repair a header

* `mmpro-send.mjs import` creates the header with its code blocks switched on. It has no conditions:
  assign it with `bricks/set-template-conditions`.
* The import adds the template's "AT - Clamp Settings" variables. Never remove them.
* `mmpro-send.mjs repair <postId> "<file>"` switches Execute code back on and puts back lost
  backslashes. It needs the file of the same version as the header. It leaves code blocks the user
  changed alone, and keeps a revision.
* The helper finds the connection in the agent's MCP settings. If it can't, ask the user for the site
  URL, their username and an application password, and set `MMPRO_SITE_URL`, `MMPRO_USER` and
  `MMPRO_APP_PASSWORD`.

### Starter templates

Starter templates come from the user's remote templates (Bricks > Settings > Templates). Ask the user
to insert one through the builder's Templates panel into the mega menu's Content, replacing Content
Inner (the starter template is itself a Content Inner). Then continue from the live tree.

### Tabbed Navigation and Adaptive Header Styling

Add-ons. The plugin edits the Mega Menu Pro header only: give the user builder steps from reference
sections 8 and 9. Tabbed Navigation goes inside a mega menu's Content, usually as a Template element
with "Render without wrapper" on.

***

## 6. Rules & gotchas

### Attributes

* `"false"` is not off. See the entry file.
* `data-caret` only shows when `data-align-content-bottom` is `true` and `--dropdown-content-gap`
  is above `0px`.
* `data-last-item-is-button` does not work on a Dropdown. Use `data-is-button` or `data-is-icon` on
  the Dropdown instead.
* `data-match-overlay-header-width` only changes anything when `data-slide-in-direction` is `top`.

### Code blocks

* Zero values need a unit: `0px`, not `0`. A bare `0` breaks Stripe style and Adaptive height.
* `--chevron-color` in the `[data-is-button]` rule is read by nothing. Use `--chevron-clr`.
* A mobile-only value goes in rule `html.dwc-mobile`, a sticky-only value in `.brx-sticky.scrolling`
  (the `rule` input of `mmpro/set-css-variables`). Do not change the `:root` value for these.
* Sidebar mode needs the `postid-23338` rules in MENU Styles / Options changed to the template's ID.
  The plugin cannot edit them: give the user the builder steps (reference section 7).
* Bricks' Templates > Import switches off Execute code on every code block, and
  `bricks/import-transfer-package` also strips backslashes. Never import an MMPro template with
  either: use `mmpro-send.mjs import`. For a header already imported that way, run
  `mmpro-send.mjs repair`.

### Your own CSS

* Global-class CSS loads in the page head, before MMPro's code blocks, so MMPro wins ties. Start every
  selector with `#brx-header` (after a leading `html…` part, if any). Never put it in front of an
  @-rule (`@font-face`, `@keyframes`, `@media`).
* A malformed rule can make Bricks leave that class's CSS off the page while the save succeeds. After
  every save, fetch the page and find one unique string from each class you saved.
* Bricks removes comments and splits `a, b {}` into one rule per selector. Never compare the stored
  CSS with what you sent.
* Desktop-only rules start with `html:not(.dwc-mobile)`, mobile-only rules with `html.dwc-mobile`. A
  rule with neither applies to both.
* A link that shows text, such as a link in a list, takes 100% of its parent's width, so the whole
  line is the click target. Icon links, and links that show only an icon on small screens, keep their
  own width: give them a tap area with `min-height` and `padding` instead.
* Keep the CSS for every class you create in one local file in your working directory, and save each
  class from it. After a fix, save only the classes that changed.
* A draft injected with `css()` loads where class CSS does, but only the published page proves it.
  Confirm there after saving.

### Stripe style and Adaptive height

* With Stripe style or Adaptive height, top-level menu items must touch. Space them with
  `--menu-item-inline-padding`, never with `gap`, margins or `justify-content: space-between` (or
  `space-around`, `space-evenly`): the pointer crossing a gap closes the dropdown and breaks the
  morph.
* A fixed `--menu-item-inline-padding` can overflow the row between the breakpoint and the container
  width. Scale it with `clamp()` so the row fits at every desktop width.
* With Adaptive height, keep a shadow on the panel (`--adaptive-height-shadow`, MMPro's default if
  the design gives none), even when the design asks for no shadows: without it the panel doesn't
  stand out on a white page.
* `stripeStyle` (JS option) morphs one shared panel, the header's `::after`, to each mega menu's
  size and position. The caret is the header's `::before`.
* The direction slide moves the children of Content Inner by 50px, and only between neighbouring
  mega menus. Old content hides at once.
* To change either, add your own CSS, for example in your content classes. Never edit MEGA MENU
  Codes.
* For a Stripe look, set the Nav's `data-hide-overlay` to `true`, and give each Content a
  `data-content-width` and `data-content-align` `center`.

### Mega menu width and position

* Width order: Content `data-content-width`, then Nav `data-global-content-width`, then
  `--dropdown-content-default-width`. A value on Content beats the global one, so clear it when the
  user asks for "all mega menus".
* Full width: Nav `data-global-content-width` = `#brx-header`, every Content `data-content-width`
  empty.
* A mega menu is capped at the screen width.

### Mobile menu

* Do not change Bricks' `mobileMenu` on Nav (Nestable).
* Mobile settings are attributes on Nav (Nestable). A Dropdown's own `data-submenu-reveal` overrides
  the Nav's.
* A new Toggle outside the Nav needs its `toggleSelector` setting set to `.brxe-nav-nested`. The
  plugin adds elements only inside Nav items and header rows. Anywhere else, this is a builder step
  for the user.
* With `data-submenu-reveal` `slide` (the default), the back button is the open item's own toggle
  `button`: `position: fixed` at the top of `.dwc-nav-wrapper`, its text (the item name, or
  `data-back-text`) in `::after`, the chevron in its `svg`.
* To stop the menu's slide-in, set `.dwc-nav-wrapper` to `transform: translateX(0) !important`, never
  `none`. The wrapper's transform keeps the back button inside the menu.
* If the header is taller than 80px on mobile (all rows together: read `--dwc-nest-header-height` on
  `body` at 390px), set `data-fullscreen-mobile-menu` to `true` on Header Pro. Otherwise the back
  button panel is as tall as the header. Tell the user you turned it on and why, with the measured
  height.
* Logged-in users have the WordPress admin bar at the top of the screen. Offset anything you fix to
  the top with `var(--wp-admin--admin-bar--height, 0px)`, as MMPro does.
* On mobile, menu items and dropdown content sit on the mobile menu's background, not the header's.
  Text that is light on a dark desktop header can end up light on a light background. When the
  header, dropdown and mobile menu backgrounds differ, set the mobile text colours with
  `html.dwc-mobile` rules or variables. In the browser, open the mobile menu and one submenu, and
  read the computed text colour and the background behind it for menu items, the back button,
  dropdown text and the last-item button. Fix anything below 4.5:1.

### Overlay header and sticky

* `data-overlay-header` on Header Pro. Per-page opt-out: `data-no-overlay` on the page's first
  section (presence only).
* The special sticky styles need Bricks' sticky header on (template settings) and both
  `data-overlay-header` and `data-sticky-overlay-special-style` set to `true`.

### Full vs Lite

* Lite has no offcanvas, sidebar, Adaptive height, Stripe style, swipe to close or auto expansion.
  Their attributes and options do not exist there. Do not add them: tell the user the feature needs
  the Full template.

***

## 7. Verification

A read-back proves configuration. Only the published page proves rendering. Say which you reached.

**A check that finds nothing is a failed check.** A selector that matches nothing returns `0` or
`null`. Prove the element was found before you report on it.

**1. Fetch the published page** with a unique query string (`?nocache=<timestamp>`). The plain URL
can be an old cached copy. The header renders for logged-out visitors on any page its conditions
cover. Grep for what you changed: an attribute value, a class, a link.

**Before you report that the header is missing from a page**, confirm the fetch was not cached
(entry file, "When the page disagrees").

**2. Use your own headless browser** for position, size, computed style and behavior:
`mmpro-test.mjs` in this folder. It needs Node 22+ and Chrome, and launches its own Chrome with a
temporary profile. The import path is relative to your script.

```js
import { open } from '../mmpro-bricks-skills/mmpro-test.mjs';
const b = await open({ url: 'https://example.com/', width: 390, height: 844, mobile: true });
try {
  await b.tap('.dwc-nest-toggle--open'); await b.settle();
  console.log(await b.box('.dwc-nav-wrapper'), b.errors);
  await b.shot('menu.png');
} finally { await b.close(); }
```

* Never attach to the user's own Chrome or ask them to relaunch theirs with a debugging port.
* Check desktop and a width below the breakpoint. Below it, also run once with `adminBar: true`.
* **MMPro's code is running** when, below the breakpoint, `document.documentElement.classList`
  contains `dwc-mobile`. If it doesn't, the code blocks did not run: code execution is off
  (Bricks > Settings > Custom code). Stop and tell the user.
* Open a dropdown with `hover(text)` on desktop or `tap(selector)` below the breakpoint, then
  `settle()` before measuring. Positions read mid-animation are wrong.
* When a rule of yours doesn't apply, run `why(selector, property)` before you change anything.
* In test URLs use `nocache` or long keys only. WordPress treats short keys such as `m`, `w`, `p` and
  `s` as its own and serves a different page.

**What you cannot check**, and must hand to the user: the builder view, builder-only attributes
(`builder-preview-content-width`, `preview-alignment`, `preview-buffer`, `data-hide-instruction`),
drafts, and logged-in-only content.

***

## 8. Agent Skills Update routing (developer sessions)

| Discovery | Where it goes |
| --- | --- |
| New operation or ability pattern | Section 5: Operations |
| Mistake to avoid, ability behavior | Section 6: Rules & gotchas |
| Attribute, variable or option | Reference file, matching section |
| Verification technique | Section 7: Verification |

One line or a short code block. Confirmed only. State it as a present-tense rule: no dates, no
story, no "an earlier version said".
