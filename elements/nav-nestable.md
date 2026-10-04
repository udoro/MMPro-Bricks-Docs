---
icon: bars
---

# Nav (Nestable)

The **Nav (Nestable)** element holds the menu. It has the class `dwc-nest-menu`. Its attributes control the menu, the mobile menu, offcanvas and sidebar modes, and mega menu width.

To change a setting, select **Nav (Nestable)**, open **Style > Attributes**, and change the value. The attributes are already there. You don't need to add them. See [Change an attribute](../README.md#change-an-attribute).

`true` turns a setting on. To turn it off, clear the value and leave the attribute in place.

Leave Bricks' own **Mobile menu > Show at breakpoint** setting on this element set to **Never**. Mega Menu Pro runs the mobile menu itself.

***

## Menu and dropdowns

| Attribute | Template value | What it does |
| --- | --- | --- |
| `data-last-item-is-button` | `true` | Styles the last menu item as a button. Use `true-2` or `true-3` for the last two or three items. Leave empty for none. Style them with the `--menu-cta-*` variables. |
| `data-last-item-is-button-alignment` | Empty | Lines up the menu items when the last item is a button: `left` or `center`. Empty is right. |
| `data-hide-overlay` | Empty | Set to `true` to hide the dark page overlay while a dropdown is open. |
| `data-overlay-on-header` | `true` | **Full.** The page overlay starts at the top of the screen instead of below the header. |
| `data-align-content-bottom` | `true` | Desktop only. Menu items fill the header's height, so dropdowns start at the bottom of the header. This may not line up if the header has top or bottom padding. `data-caret` needs it. |
| `data-caret` | Empty | Set to `true` to show a small arrow on dropdowns. It needs `data-align-content-bottom` set to `true` and `--dropdown-content-gap` above `0px`. |
| `data-align-dropdown-top` | `true` | Nested submenus in a multilevel dropdown line up with the top of the dropdown, not with their parent item. |
| `data-optimize-stripe` | `true` | **Full.** Improves the look of Stripe style. |

***

## Mega menu width and position

See [Mega Menu Content](../mega-menu-content.md) for how these work together.

| Attribute | Template value | What it does |
| --- | --- | --- |
| `data-global-content-width` | `1080` | Width of every mega menu. Takes a number, a CSS variable or a selector. A dropdown's own `data-content-width` wins over it. |
| `data-global-content-vertical` | Empty | A selector, such as `#brx-header`. Mega menus and dropdowns start at the bottom of that element. |
| `builder-preview-content-width` | Empty | Builder only. Width of mega menus in the builder, in pixels. |
| `preview-buffer` | Empty | Builder only. Set to `true` to see the `--dropdown-buffer` area in red. |

In Lite, this element also has a `style` attribute. Don't change it.

***

## Mobile menu

See [Mobile, Offcanvas & Sidebar](../mobile-offcanvas-sidebar.md).

| Attribute | Template value | What it does |
| --- | --- | --- |
| `data-slide-in-direction` | Empty | Where the menu opens from: `left`, `top`, `bottom`, `left top` or `left bottom`. Empty is right. |
| `data-submenu-reveal` | `slide` | How submenus open: `slide` or `expand`. A **Dropdown** element can set its own value. |
| `data-below-header` | Empty | Set to `true` to open the menu below the header. |
| `data-match-overlay-header-width` | `true` | With an overlay header on mobile and `data-slide-in-direction` set to `top`, the menu matches the header's width and grows down from it. Also works without an overlay header. |
| `data-back-text` | `auto` | Text of the back bar. `auto` uses the menu item's name, such as "Back to Products". Any other text replaces it. |
| `data-hide-close-bar` | Empty | Set to `true` to hide the back bar. |
| `data-mobile-top-transparent` | `true` | Places the back bar over the header, with the logo showing until a submenu opens. Leave it on. |
| `data-show-mobile-logo` | Empty | Set to `true` to show the logo in the mobile menu. |
| `data-show-toggle-always` | `true` | Keeps the menu toggle on screen while a submenu is open. Works when `data-submenu-reveal` is `slide`. |
| `data-tooltip-back-text` | `Swipe > back to` | **Full.** Text of the swipe hint. Needs the `swipeToClose` and `toolTip` options on. |
| `data-single-back-button` | `true` | When a [Tabbed Navigation](../add-ons/tabbed-navigation.md) is inside a mega menu, shows one back button on mobile instead of two. |

***

## Offcanvas and sidebar

**Full only.**

| Attribute | Template value | What it does |
| --- | --- | --- |
| `data-offcanvas` | Empty | Set to `true` to use offcanvas navigation on desktop. |
| `data-overlay-sidebar` | Empty | Set to `true` to float the sidebar over the page. |
| `data-sidebar-back-text-on-logo` | Empty | Set to `true` to line up the back bar with the top of the header in sidebar mode. |

***

## Older versions

* `data-force-backtext` was replaced in 1.4. Use `data-back-text` with `auto` or your own text.
* `data-caret-outline` is no longer used.
