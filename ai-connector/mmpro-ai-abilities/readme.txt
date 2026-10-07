=== MMPro AI Abilities ===
Requires at least: 6.9
Requires PHP: 7.4
Stable tag: 0.4.1
License: GPL-2.0-or-later

Lets AI agents read and edit Mega Menu Pro headers in Bricks.

== Description ==

Adds twelve abilities to the WordPress Abilities API. The WordPress MCP Adapter makes them available to AI agents, next to Bricks' own abilities:

* mmpro/get-header: find the Mega Menu Pro header and summarise it
* mmpro/set-attributes: set attributes on Header Pro, the Nav (Nestable), a Dropdown or a Content element
* mmpro/set-css-variables: set CSS variables in the MENU Styles / Options code block
* mmpro/set-js-options: set options in the MENU Styles / Options JavaScript
* mmpro/set-menu-item: change a menu item's text or link
* mmpro/duplicate-element: copy a menu item, dropdown or mega menu
* mmpro/remove-element: remove a menu element
* mmpro/add-elements: add elements inside a mega menu (mega menu content always goes inside a Content Inner)
* mmpro/set-element-settings: change the settings of an element in the menu
* mmpro/set-breakpoint: move the desktop/mobile breakpoint
* mmpro/import-header: import the Mega Menu Pro header template, with its code blocks switched on
* mmpro/repair-code-blocks: switch the header's code blocks back on and fix them after an import or a revision restore

Every write saves a revision, so you can undo it from the header template's Revisions panel in Bricks. Writes only work on a header template with a "Header Pro" element, and only on the menu inside Nav items or on header rows (blocks directly inside Header Pro). The header structure can't be changed. The code blocks marked "don't edit" only change through the documented breakpoint change, or when a repair puts back the template file's own code.

== Installation ==

1. In WordPress, go to Plugins > Add New Plugin > Upload Plugin.
2. Choose the mmpro-ai-abilities zip file and click Install Now.
3. Click Activate.

You also need Bricks 2.4 or later with abilities turned on (Bricks > AI), and the WordPress MCP Adapter plugin.

== Who can use it ==

An agent can only do what its WordPress user can do. Reading needs the right to edit the template. Writing also needs Bricks builder access. Changing CSS variables or JS options needs Bricks' code execution permission. Adding or removing elements needs permission to change the element count. Importing a header or repairing its code blocks needs Bricks' code execution permission too.

== Changelog ==

= 0.4.1 =
* Your agent can now add extra rows to your header, such as a top bar above the menu.

= 0.4.0 =
* Your agent can now import the Mega Menu Pro header template for you, with its code blocks switched on. It can also fix code blocks after an import or a revision restore switched them off or broke them.

= 0.3.3 =
* New versions show up sooner, and Check again in Dashboard > Updates finds them straight away.

= 0.3.2 =
* The newest revision now matches your header, as in Bricks. To undo an agent's change, apply the revision just below Current version.

= 0.3.1 =
* Fixed: undoing a change from the header template's revisions in Bricks now works.

= 0.3.0 =
* New versions now show in Dashboard > Updates, like any other plugin.

= 0.2.0 =
* New: change the settings of an element in the menu.
* New: move the breakpoint.
* Mega menu content must go inside a Content Inner, and a dropdown's Content can't be removed on its own.

= 0.1.0 =
* First version.
