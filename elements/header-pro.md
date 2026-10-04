---
icon: window-maximize
---

# Header Pro

**Header Pro** is the outer element of the header. In the Structure panel it's labelled "Header Pro | v1.4.5". It holds the header settings, mainly the overlay header.

To change a setting, select **Header Pro**, open **Style > Attributes**, and change the value. The attributes are already there. You don't need to add them. See [Change an attribute](../README.md#change-an-attribute).

`true` turns a setting on. To turn it off, clear the value and leave the attribute in place.

***

## Attributes

| Attribute | Values | What it does |
| --- | --- | --- |
| `data-overlay-header` | `true` | The header floats over the top of the page, for example over a hero image. Style it with the `--overlay-header-*` variables. |
| `data-overlay-header-mobile` | `true` | Uses the overlay header on mobile too. |
| `data-allow-overlay-mobile-opacity` | `true` | Lets the overlay header be see-through on mobile, so its blur shows. See [the note below](#see-through-overlay-header-on-mobile). |
| `data-overlay-header-optimize-adaptive-height` | `true` | **Full.** Improves the Adaptive height look on an overlay header. Use it when the `adaptiveHeight` option is on. |
| `data-overlay-header-no-top-gap` | `true` | Removes the gap above the overlay header, so it touches the top of the screen with square top corners. |
| `data-fullscreen-mobile-menu` | `true` | Sets up the mobile menu for a header with more than one row. Your logo is added to the menu. |
| `data-hide-mobile-logo` | `true` | Hides the logo that `data-fullscreen-mobile-menu` adds. |
| `data-offset-section-padding` | `true` | Adds top padding to the first section of each page, so the overlay header doesn't cover its content. Set the amount with `--overlay-offset-padding`. |
| `data-fix-centered-logo-fouc` | `true` | Stops the menu from jumping as the page loads when the logo is centered. |
| `data-sticky-overlay-special-style` | `true` | Turns on the sticky and overlay preset styles. See [Styling](../styling.md#sticky-header-with-overlay-special-styles). |
| `data-mobile-special-style` | `slide-split` | Gives the mobile menu a preset reveal on tablets. Leave empty to turn it off. |

***

## Turn a setting off on one page

Add these attributes to the first section or div of a page, and leave their **Value** empty. See [Change an attribute](../README.md#change-an-attribute).

| Attribute | What it does |
| --- | --- |
| `data-no-overlay` | Turns off the overlay header on this page. |
| `data-no-overlay-offset-padding` | Turns off the offset padding on this page. |

***

## See-through overlay header on mobile

On mobile, the overlay header is solid by default, because some mobile menu styles need it. To keep it see-through on mobile, with its blur, set `data-allow-overlay-mobile-opacity` to `true`.

This changes one thing. When the menu opens from the top and `data-match-overlay-header-width` is `true`:

* With this attribute on, the menu looks like it slides down from behind the header.
* With it off, the header looks like it grows down to show the menu.

Set how see-through the header is with an `rgba` color in `--overlay-header-bg`, for example `rgb(255 255 255 / 70%)`.
