---
icon: sliders
---

# Menu Options

Menu options control how the menu behaves. They live in the JS tab of the **MENU Styles / Options** code block.

To change an option:

1. Select the **MENU Styles / Options** code block.
2. Open its JS tab.
3. Change the value after the option's name. `1` and `true` turn an option on. `0` and `false` turn it off.
4. Keep the comma at the end of the line, and keep the quotes around text values such as `'#brx-header'`.

***

## Menu options

These are inside `const MegaMenuCONFIG = { ... }` at the top of the JS tab. Options marked **Full** aren't in Lite.

| Option | Default | What it does |
| --- | --- | --- |
| `minWidth` | `1201` | The screen width where the desktop menu starts. To change it, see [Breakpoint](#breakpoint). |
| `menuAutoExpansion` | `true` | **Full.** Opens the dropdown that holds the current page's link when the page loads. Works in the mobile menu, offcanvas and sidebar. |
| `swipeToClose` | `true` | **Full.** Lets visitors swipe to go back or close the mobile menu. |
| `toolTip` | `true` | **Full.** Shows a hint about swiping. Set the hint text with the `data-tooltip-back-text` attribute on [Nav (Nestable)](elements/nav-nestable.md). |
| `adaptiveHeight` | `0` | **Full.** Animates the height smoothly when you move between mega menus of different heights. |
| `stripeStyle` | `0` | **Full.** The panel behind the dropdowns morphs to the size and position of each one. If both this and `adaptiveHeight` are on, Stripe style wins. See [Stripe style](#stripe-style). |
| `headerSelector` | `'#brx-header'` | The header element. Only change it if your header has a different ID. |
| `nestMenuSelector` | `'.dwc-nest-menu'` | The Nav (Nestable) element. Don't change it. |
| `closeNavOnClick` | `1` | Closes the menu when a visitor clicks a link, so the site feels quick while the next page loads. |
| `closeOnHashClickOnly` | `0` | When `1`, only links to a spot on the same page (with `#`) close the menu. |
| `closeOnMobileOnly` | `0` | When `1`, links only close the menu on mobile. |
| `closeNavOnClickExclude` | `'.js-wpml-ls-item-toggle'` | Elements that don't close the menu when clicked. Separate selectors with a comma, for example `'.class-one, #id_one, tag'`. |
| `breakinToNavList` | `1` | Where the breakin container goes in the mobile menu. `1` puts it in the list of menu items. `0` puts it at the end of the nav wrapper. See [Moving Elements](moving-elements.md#data-breakin). |
| `shiftFactor` | `1` | How far to shift a dropdown that runs off the edge of the screen. |
| `minOverflow` | `10` | How many pixels a dropdown must run off the screen before it's shifted. |
| `reinitializeOnURLchange` | `true` | Starts the menu again when the URL changes. Keep it on if you use page transitions. |
| `overlayInsideHeader` | `0` | When `1`, the page overlay and its blur also cover the header. This doesn't work when the `data-overlay-header` attribute is `true`. |

***

## Centered logo

These options are inside `const CenteredLogoCONFIG = { ... }`, below the menu options in the JS tab. They place your logo in the middle of the menu items on desktop.

| Option | Default | What it does |
| --- | --- | --- |
| `enable` | `0` | Set to `1` to center the logo. |
| `centerGuide` | `1` | Shows a guide on the page so you can check the logo is centered. Only logged-in users see it. |
| `forceCenteredLogo` | `1` | Shifts the menu so the logo sits in the exact center of the screen. |
| `centerNudge` | `0` | Moves the menu left (negative number) or right (positive number), in pixels. |
| `allowOddItems` | `1` | Allows a centered logo when you have an odd number of menu items, such as 5 or 7. |
| `roundOffFactor` | `'before'` | With an odd number of items, puts the logo `'before'` or `'after'` the middle item. |

If the menu jumps when the page loads, set the `data-fix-centered-logo-fouc` attribute on [Header Pro](elements/header-pro.md) to `true`.

Use this feature rather than placing the logo in the menu yourself.

***

## Stripe style

**Full only.** Stripe style needs the top of each mega menu to sit at the bottom of the header. It lines them up with `#brx-header` for you.

If your mega menus don't line up, set the `data-global-content-vertical` attribute on [Nav (Nestable)](elements/nav-nestable.md) to your header's selector.

To get the best look, also set the `data-optimize-stripe` attribute to `true`.

***

## Breakpoint

The breakpoint is where the desktop menu switches to the mobile menu. By default, the desktop menu shows from 1201px and the mobile menu at 1200px and below.

You set it in three code blocks. Change all three, or the menu breaks between the two values.

1. **MENU Styles / Options**, JS tab: set `minWidth` to your new desktop width, for example `1025`.
2. **MEDIA QUERY** (called **MENU Media Query** in Lite), CSS tab: change both `1200px` values to your new mobile width, for example `1024px`.
3. **MEGA MENU Codes**, CSS tab: change the one `1201px` to your new desktop width, for example `1025px`. Then change every `1200px` to your new mobile width. Full has two, Lite has one.

The desktop width is always 1px more than the mobile width.

{% hint style="danger" %}
**Don't raise the breakpoint to show the mobile menu on desktop.** Use offcanvas mode instead. See [Mobile, Offcanvas & Sidebar](mobile-offcanvas-sidebar.md#offcanvas-navigation).
{% endhint %}

[Tabbed Navigation](add-ons/tabbed-navigation.md) has its own breakpoint. If you use it, change it to match. See [its breakpoint steps](add-ons/tabbed-navigation.md#breakpoint).
