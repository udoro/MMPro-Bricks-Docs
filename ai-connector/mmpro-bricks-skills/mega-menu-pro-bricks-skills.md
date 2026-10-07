---
icon: sparkles
---

# AI Skills Reference

Entry point for configuring Mega Menu Pro + Header Builder for **Bricks** through the Bricks MCP
abilities.

**This file is short on purpose. Read all of it, then read only what it sends you to.**

| File | What it is | When to read it |
| --- | --- | --- |
| `mega-menu-pro-bricks-skills-build.md` | Workflow, ability calls, element shapes, rules and gotchas | In full, on the build path only |
| `mega-menu-pro-bricks-skills-reference.md` | Element map, attribute tables, CSS variables, JS options | Never in full. Grep one section |
| `mmpro-test.mjs` | Headless Chrome helper: open the page, act, measure, screenshot, measure screenshots | Import it when the build file's Verification needs a browser. Don't read it unless it fails |
| `mmpro-send.mjs` | Sends a template file to the site to import or repair a header | Run it when the build file says. Don't read it unless it fails |

***

## Is the site connected

Only a few high-level Bricks abilities are direct tools. Every ability this skill uses
(`mmpro/get-header`, the other `mmpro/*` abilities, and `bricks/*` ones) goes through the dispatcher
tool `mcp-adapter-execute-ability`, with `ability_name` (for example `"mmpro/get-header"`) and
`parameters`. `mcp-adapter-get-ability-info` returns an
ability's input schema. If your client defers MCP tools, load the dispatcher by name first. A
missing direct tool does not mean a missing ability.

* **No `mcp-adapter-execute-ability` tool:** stop. Tell the user: "I can't reach your Bricks site.
  Follow the AI Connector setup, then start a new session." Do not try REST calls, PHP or a browser
  instead.
* **An ability is disabled:** call `bricks/list-ability-status`, name the disabled ability to the
  user, and stop that operation. Do not route around it.
* **`mmpro/get-header` is not available:** the MMPro AI Abilities plugin is missing. You may read,
  but stop before any write and tell the user: "Install and activate the MMPro AI Abilities plugin,
  then start a new session."

If your client also has the general Bricks skills (`bricks:*`), load the matching one for generic
Bricks work such as query loops, conditions or template settings. This file covers what is specific
to Mega Menu Pro.

***

## Which path are you on

**Correction path:** you are changing something that already exists: an attribute value, a CSS
variable, a JS option, a menu item's text or link.

**Build path:** you are creating structure: new menu items, a new dropdown or mega menu, mega menu
content, a new header, or a rebuild.

If you cannot tell, you are on the build path. If a correction turns out to need new structure, stop
and switch at that point.

***

## Correction path

Read these three things and nothing else:

1. **The invariants below.**
2. **Section 6 (Rules & gotchas) in `mega-menu-pro-bricks-skills-build.md`**, grepped for the
   feature you are touching.
3. **One row** of the reference file, for the attribute, variable or option you are changing.

Then find the header (below), change the one value, verify, stop.

**Do not** read the build file end to end, read the code blocks marked "don't edit", re-read the
header tree after every write, or inventory anything you are not touching.

### Invariants that apply on every path

* **Write only with `mmpro/*`.** Bricks' own element-write abilities reject MMPro headers. Build file,
  section 5.
* **Off is `""`, never `"false"`.** Some attributes are read by presence alone (`data-breakout-link`,
  `data-breakin`, `data-breakinto`, `data-is-button`, `data-is-icon`, `data-no-overlay`,
  `data-no-overlay-offset-padding`): turn those off with `null`, which removes them.
* **Pass the latest digest as `expectedDigest`** on every write, from `mmpro/get-header` or the
  previous write's response.
* **Write the value the code reads**, not a label: `slide-split`, `true-2`, `left top`. The reference
  lists every accepted value.
* **Element IDs and global class IDs are site-local.** Resolve them from the live tree every
  session. Never use an ID from any document, including this one.
* **You edit one code block: MENU Styles / Options**, through `mmpro/set-css-variables` and
  `mmpro/set-js-options`. The others hold product code: never change them, except the documented
  breakpoint change. To change behaviour beyond variables and options, add your own CSS in a
  class's custom CSS or a separate stylesheet.
* **All mega menu content goes inside Content Inner** (Dropdown > Content > Content Inner, tag `li`).
  Never add anything else directly to a mega menu's Content. Mega Menu Pro needs this structure for
  every feature.
* **A success response proves the value persisted, not that it rendered.** Check the published
  page before you call a change done, and say which level you reached.
* **If MMPro's code stops running**, check that code execution is on (Bricks > Settings > Custom
  code). Stop and tell the user; do not retry the write.

### Find the header

Call `mmpro/get-header` with no input. It finds the Mega Menu Pro header template and returns its
element IDs (Header Pro, Nav (Nestable), Nav items, MENU Styles / Options), attributes, menu tree,
JS options and `digest`. If it lists more than one header, ask the user which, then call it again with
`postId`. If it finds none, see the build file, section 2.

The label carries the version: `Header Pro | v1.4.6` is Full, `Header Pro | v1.4.6 Lite` is Lite.
The reference describes 1.4.6 and 1.4.5. For any other version, compare the live attribute names on Header Pro
and Nav (Nestable) with the reference's Fingerprint section. Where they differ, the live element is
authoritative and the reference is not.

### When the page disagrees with the builder

**Always fetch a published page with a unique query string** (`?nocache=<timestamp>`). Hosts and CDNs
serve old copies of the plain URL, and an old copy can be missing the whole header. A response header
reporting a cache hit (`x-hcdn-cache-status`, `cf-cache-status`, `x-cache`, `x-litespeed-cache`)
means the copy may predate your change: fetch again with a new query string.

Cheapest cause first, stop at the first hit: **did the write land on the template you checked**
(the ability response names the post ID), **was the copy cached** (above), **does this template
apply to this page** (template conditions). Three checks is the budget. If none explains it, report
the discrepancy and stop. Never sweep other pages or audit the site.

***

## Build path

Read `mega-menu-pro-bricks-skills-build.md` in full, then follow its workflow from "START HERE".
Everything above still applies.

***

## Agent Skills Update

Where you record a finding depends on the session. Decide before writing anything. A session is a
**developer** session when `MMPRO BRICKS/mmpro-bricks-dev-context.md` is found by searching upward
from this file's folder, and a **user** session otherwise.

| Session | Record findings in | Never edit |
| --- | --- | --- |
| **Developer** | these skills files, routed by the table in the build file | `mmpro-bricks-user-context.md` |
| **User** | `mmpro-bricks-user-context.md`, in this folder | these skills files |

**If you cannot tell, treat it as a user session.** On a user install these files are overwritten by
the next update.

**Ask before you write, in either session.** Show the exact text, name the file and section, and
wait for agreement. If the answer is no, drop it rather than recording it somewhere else.

***

## Before you say you are done

End every task with a short report. Five lines, not a paragraph.

**Time.** Always give it. Run `date +%s` when you start and again at the end. If the task spans
several messages, save the start value in a file, so a context summary can't lose it.

**Calls.** How many Bricks ability calls you made.

**What changed.** Elements, attributes, variables and options you touched, by name. Name templates
and elements by their title, not only their ID. If you replaced
or deleted anything, say so first.

**Custom code.** Name every place that now holds CSS or JS that MMPro doesn't ship: each global
class, element, code block or stylesheet, and what it styles. Say where the mobile rules are. Write
`Custom code: none` when you added none, and `No custom JS` when it is all CSS.

**What you verified, and how.** Say which level you reached: persisted (read-back) or rendered
(published page). Name anything you could not check as unverified.

**Do not report tokens or cost.** You cannot measure them.

```
4m 10s, 6 Bricks calls.
Changed: Nav (Nestable) data-slide-in-direction "" -> "left"; --menu-item-clr #000 -> #1a1a2e.
Custom code: none.
Verified: rendered. Published page at 390px opens the menu from the left; computed color matches.
Unverified: builder view.
```
