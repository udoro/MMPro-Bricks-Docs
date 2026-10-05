---
icon: arrows-retweet
---

# Changelog

> To update Mega Menu Pro, see **Updating** in the Docs tab. Each version also comes as a template file. Import it in **Bricks > Templates**. Don't copy and paste it.

## Version 1.4.6 - October 5, 2026

_Update from 1.4.5: replace the CSS in the **MEGA MENU Codes** code block. You don't need to import the template again._

### Improved

* **Stripe style slides in from the right side on every move**, even when you skip a menu, like going from the first mega menu straight to the third.
* **Stripe style fades out the old content** while the new content comes in.

***

## Version 1.4.5 - May 21, 2026

### New

* **Separator lines between menu items.** Set them with the `--menu-item-separator-width`, `--menu-item-separator-color` and `--menu-item-separator-inset` variables.

### Improved

* Works better with Tabbed Navigation 1.3.

### Fixes

* The mobile close button no longer opens or closes other offcanvas elements on the page.
* The special mobile styles look right when submenus expand in place.
* The swipe-to-close tooltip no longer shows when submenus expand in place.
* Various minor bug fixes.

***

## Version 1.4.4 - January 22, 2026

### Improved

* Better support for page transitions (the Page Transition API, as used by BricksForge).
* Better size adjustment for the mobile menu toggle.

### Fixes

* Minor styling fixes for the special mobile styles.

***

## Version 1.4.3 - December 13, 2025

### New

* Full RTL support.
* **Keep a link out of the active style.** Add the class `dwc-exclude` to the link, or to an element that holds it.

### Improved

* A mega menu can close when a visitor clicks a link to a spot on the same page (a `#` link).
* **Links to a spot on the same page leave room for a sticky header**, so the header doesn't cover the section.
* Better integration with Tabbed Navigation.
* Several UI and UX refinements.

### Changed

* **Elements you move into the mobile menu now go in the list of menu items by default.** To put them at the end of the nav wrapper instead, set the `breakinToNavList` option to `0` in the **MENU Styles / Options** code block (JS tab).

### Fixes

* A top bar outside the header no longer breaks Stripe style or Adaptive height.
* The mobile menu no longer overlaps the header when a top bar sits outside the header.
* Works with Bricks' new sticky header class, `.brx-sticky`, which replaced `.sticky`.
* Moving an element into the list of menu items no longer breaks "last item is button".
* Several edge-case bug fixes.

***

## Version 1.4.2 - October 3, 2025

### Improved

* CSS variables are reorganized, so they're easier to find and style.

### Changed

* **Elements moved into the list of menu items no longer sit at the bottom of the mobile menu by default.** To keep them at the bottom, set the `data-breakin` attribute to `end`.

### Fixes

* Mega menus no longer wait 1 second before opening.
* The swipe-to-close element no longer sits inside the list of menu items.

***

## Version 1.4.1 - August 22, 2025

### New

* **Line up every dropdown with any element.** Set the `data-global-content-vertical` attribute on the Nav (Nestable) element to a selector. It works for multilevel dropdowns too.
* **Choose whether clicking a link closes the menu:** always, never, or only for links to a spot on the same page. Use the `closeNavOnClick` and `closeOnHashClickOnly` options in the **MENU Styles / Options** code block (JS tab).
* **Move breakin elements into the list of menu items** with the `breakinToNavList` option.
* **Make a dropdown button look like a button or an icon** with the `data-is-button` and `data-is-icon` attributes.
* **Move any element into any container below a screen width** with the `data-breakinto` attribute, for example `data-breakinto=".my-container | 767"`.

### Improved

* **The `data-content-width` and `data-global-content-width` attributes take any value:** a number like `1080`, a number with a unit like `1080px`, a selector (class, ID, tag or attribute), or a CSS variable like `var(--content-width)`.
* **Breakin and breakout links are simpler.** Set the screen width right in the `data-breakin` or `data-breakout-link` attribute. You don't need the `data-breakpoint` attribute anymore.
* With Stripe style, dropdowns line up with the bottom of the header on their own.
* Better Tabbed Navigation integration.
* Several quality-of-life improvements.

### Fixes

* The back button shows on iOS when the nav wrapper scrolls.
* The mobile menu lines up correctly when the WordPress admin bar is showing.
* No more blue tap highlight in Chromium-based browsers.
* Several bug fixes.

***

## Version 1.4 - July 8, 2025

### New

* Bricks 2.0 compatibility.
* Tabbed Navigation (also called the WooCommerce Mega Menu).
* **Adaptive Header Styling.** The header and menu colors change as you scroll over different backgrounds.
* **Breakin.** Move any content in your header into the mobile menu below a screen width you choose.
* Special tablet split navigation.
* **Special sticky overlay header style.** The menu and header colors change when the header is sticky.
* The mobile menu scrolls back to the top when it closes.

### Improved

* **Breakout links take a screen width**, so a link can break out on tablets but not on phones.
* Set a mega menu's width with an attribute, using a number or a selector.
* Set one width for all mega menus with an attribute, using a number or a selector.
* Better special mobile style for the overlay header.
* An option to go back to the default dropdown alignment, lined up with the top of the parent item.
* Lots of bug fixes and quality-of-life improvements.

### Changed

* The `data-force-backtext` attribute is replaced. To change the back text, use the `data-back-text` attribute.

***

## Version 1.3 - May 29, 2025

### New

* **Auto centered logo.** Center your logo between the menu items.
* **Breakout link.** The `data-breakout-link` attribute moves a link, like a CTA button, out of the mobile menu and into the header. It also helps when you center a logo by hand.

### Improved

* Edit the back text and the "Swipe to go back" tooltip with the `data-back-text` and `data-tooltip-back-text` attributes.
* The special mobile menu style for the overlay header works with slide submenus.
* Smoother animation and styling for the special mobile menu style in the overlay header.
* Better adaptive height in overlay headers, with the new `data-overlay-header-optimize-adaptive-height` attribute.
* Keep the mobile toggle showing while a submenu is open, with the new `data-show-toggle-always` attribute.

### Fixes

* Focus no longer gets lost when a dropdown sublevel is shorter than its parent.
* Hover styles no longer double up when top-level dropdown buttons hold links.
* The header no longer breaks when the breakpoint is set below 1024px.
* Mega menu content lines up correctly in Stripe style.
* With the overlay header's special mobile menu style, the toggle closes the menu on the first click when a submenu is open.

***

## Version 1.2.1 - April 20, 2025

### Fixes

* Hotfix: editing the mobile menu was broken in some cases.

***

## Version 1.2 - April 19, 2025

### New

* **The last 3 menu items can be buttons**, each with its own style.
* Carets on every dropdown style, not just Stripe style.
* A gap setting between the header and the dropdowns. Handy with carets.
* **Dropdown top alignment.** Line up the top of a dropdown with the bottom of the header, or with its dropdown button.
* The overlay header on mobile.
* **A mobile menu that matches the overlay header.** When the menu slides in from the top and submenus expand in place, it can match the header's width, round its bottom corners and fit its height to the content.
* Live alignment preview in the Bricks builder.
* **Mega Menu Pro Lite.** A smaller template with just the mega menu navigation.

### Fixes and improvements

* Submenu reveal types work the same everywhere.
* The caret no longer shows before the dropdown content in Stripe style.
* Stripe style dropdowns line up correctly.
* Multilevel dropdown arrows point the right way on desktop.
* Focus no longer gets lost when a dropdown flyout is shorter than its parent.
* The page overlay no longer covers the wrong elements.
* The auto mobile logo supports SVG.
* Better code quality and general usability.

***

## Version 1.1 - April 3, 2025

### New

* Support for the BricksExtras Header Row.
* The overlay header on desktop.
* Custom mega menu size and position.
* More styling options.
* Multi-row headers, with a better mobile menu for them.
* **A tidier Structure panel.** The extra `<div>` elements for the overlay, adaptive height and menu width are gone.
* Full RTL (right-to-left) support.

### Fixes and improvements

* No more large layout shift (CLS) when the page loads.
* No more flash of unstyled content (FOUC) when the page loads.
* No more content gap issues with Automatic.css.
* Better code quality and speed.
* Easier editing of offcanvas and sidebar modes in the builder.
* Instructions on some elements in the builder.
* General bug fixes and refinements.

***

## Version 1.0 - March 18, 2025

* First release.
