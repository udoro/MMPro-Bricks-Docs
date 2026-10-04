---
icon: arrows-retweet
---

# Changelog

> To update Mega Menu Pro, see **Updating** in the Docs tab.

## Version 1.4.3

### Changed

* **Elements you move into the mobile menu now go in the list of menu items by default.** To put them at the end of the nav wrapper instead, set the `breakinToNavList` option to `0` in the **MENU Styles / Options** code block (JS tab).

### Fixes

* **Moving an element into the list of menu items no longer breaks "last item is button".** Your last menu item keeps its button style.

***

## Version 1.4.2

### Changed

* **Elements moved into the list of menu items no longer sit at the bottom of the mobile menu by default.** To keep them at the bottom, set the `data-breakin` attribute to `end`.

***

## Version 1.4.1

### New

* **Move any element anywhere below a screen width** with the `data-breakinto` attribute. Set it to the class or ID of the place you want it to go, like `.my-div`. To pick the width, add it after a bar: `.my-div | 767`.
* **Set where every mega menu and dropdown opens** with the `data-global-content-vertical` attribute on the Nav (Nestable) element. Give it a selector, such as `#brx-header`.
* **Make a dropdown button look like a button or an icon** with the `data-is-button` and `data-is-icon` attributes.
* **Choose whether clicking a link closes the menu** with the `closeNavOnClick` option in the **MENU Styles / Options** code block (JS tab).

### Improved

* **The `data-content-width` and `data-global-content-width` attributes take any value.** Use a number with or without `px`, a CSS variable, or a selector.
* **Set your own screen width right in the `data-breakout-link` and `data-breakin` attributes,** like `data-breakin="767"`. You don't need a separate `data-breakpoint` attribute anymore.

***

## Version 1.4

### New

* **Set a mega menu's width** with the `data-content-width` attribute on its Dropdown content. Give it a number, or a selector to match that element's width.
* **Set one width for all your mega menus** with the `data-global-content-width` attribute on the Nav (Nestable) element. A mega menu's own `data-content-width` still wins.

### Deprecated

* The `data-force-backtext` attribute. To change the back text, use the `data-back-text` attribute.
