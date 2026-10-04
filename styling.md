---
icon: palette
---

# Styling

You style Mega Menu Pro with CSS variables. They live in the CSS tab of the **MENU Styles / Options** code block.

To change a style:

1. Select the **MENU Styles / Options** code block.
2. Open its CSS tab.
3. Find the variable. Most are in the `:root` rule near the top.
4. Change only its value: the part between the colon and the semicolon. Keep the semicolon.

Don't add CSS to the code blocks marked "don't edit". See [Code Blocks](elements/code-blocks.md).

{% hint style="warning" %}
**Always give zero a unit.** Write `0px`, not `0`. Some calculations break on a bare `0`, and the dropdowns end up in the wrong place. Some code managers, such as WPCodeBox, strip the unit from `0px` on the frontend. If you manage the variables there, use `0.1px` instead.
{% endhint %}

***

## Mobile styles

The first rule in the CSS tab applies to the mobile menu only:

```css
html.dwc-mobile {
  --mobile-menu-width: min(450px, 100%);
  --menu-item-font-size: 18px;
  --dropdown-item-font-size: var(--menu-item-font-size);
  --back-text-font-size: 16px;
  --menu-item-hover-border-bg: initial;
}
```

To give the mobile menu a different value, copy a variable into this rule and change it there.

You don't need to copy variables that are already for mobile, such as `--mobile-menu-bg`. They only affect the mobile menu anyway.

***

## Sticky header styles

To style the header once it's sticky, copy variables into this rule and set new values:

```css
.brx-sticky.scrolling {
  /* add your sticky styles variable here */
}
```

Turn on the sticky header first: in the builder, open **Settings > Template Settings > Header** and turn on **Sticky header**.

***

## Variables

The tables list the defaults from the Full template. Variables marked **Full** aren't in Lite.

### Colors and background

| Variable | Default | What it does |
| --- | --- | --- |
| `--primary-clr` | `orangered` | Accent color. Used for hover, active and CTA hover colors. |
| `--header-bg` | `white` | Header background. |
| `--dropdown-content-bg` | `white` | Background of dropdowns and mega menus. |
| `--mobile-menu-bg` | `var(--header-bg)` | Mobile and offcanvas menu background. |
| `--mobile-menu-topbar-bg` | `var(--header-bg)` | Background of the top bar in the mobile menu. |

### Width, height and spacing

| Variable | Default | What it does |
| --- | --- | --- |
| `--mobile-menu-width` | `min(300px, 100%)` | Width of the mobile and offcanvas menu. |
| `--mobile-logo-height` | `50px` | **Full.** Logo height when the mobile menu is full screen. |
| `--multilevel-dropdown-width` | `200px` | Width of multilevel dropdowns. Set it to `max-content` to fit the longest link instead of wrapping. |
| `--dropdown-content-gap` | `0px` | Gap between the header and the dropdown. Always add a unit. |
| `--header-min-height` | `60px` | Minimum header height. |
| `--fullscreen-mobile-menu-top-height` | `60px` | Height of the top bar when the mobile menu is full screen. |
| `--top-offset` | `40px` | Height of the mobile menu's top bar when the menu opens below the header. |
| `--dropdown-content-default-width` | `1120px` | Default mega menu width. Also the width you see in the builder preview. |
| `--header-inline-padding` | `clamp(1.5rem, calc(0.625vw + 1.375rem), 1.875rem)` | Left and right padding of the header. |
| `--dropdown-buffer` | `inherit` | Extra space in px under a menu item, so the mouse can move at an angle to the mega menu without closing it. |

### Borders, shadows and backdrop

| Variable | Default | What it does |
| --- | --- | --- |
| `--dropdown-content-border-radius` | `0px` | Corner radius of dropdowns. |
| `--dropdown-content-shadow` | `0px 5px 15px -10px rgb(0 0 0 / 0%)` | Dropdown shadow. |
| `--dropdown-content-border-size` | `1px` | Dropdown border width. Keep it at `1px` or more. |
| `--dropdown-content-border-color` | `var(--dropdown-content-bg)` | Dropdown border color. |
| `--nav-overlay-backdrop-blur` | `0px` | Blur on the page behind an open menu. |
| `--nav-overlay-backdrop-clr` | `rgba(0, 0, 0, 0.3)` | Color of the page overlay behind an open menu. |
| `--sidebar-shadow` | `0px 0px 2px rgba(0, 0, 0, 0.7)` | **Full.** Sidebar navigation shadow. |
| `--mobile-menu-radius` | `var(--overlay-header-radius)` | Corner radius of the mobile menu when it opens from an overlay header. |
| `--slide-out-speed` | `1.3` | Speed of the submenu slide. A higher number is faster. |

### Menu toggle (hamburger)

| Variable | Default | What it does |
| --- | --- | --- |
| `--menu-toggle-clr` | `var(--menu-item-clr)` | Open toggle color. |
| `--menu-close-toggle-clr` | `var(--menu-item-clr)` | Close toggle color. |
| `--menu-toggle-hover-clr` | `var(--menu-item-hover-clr)` | Toggle hover color. |
| `--open-icon-size` | `40px` | Toggle size. |
| `--open-icon-align` | `0` | `0` puts the toggle on the right. `auto` puts it on the left. |
| `--open-icon-horizontal-offset` | `0px` | Moves the toggle left or right from the edge of the screen. |
| `--open-icon-close-offset` | `1.2` | Width of the close icon's lines, compared to the open icon. |

### Menu items

| Variable | Default | What it does |
| --- | --- | --- |
| `--menu-item-clr` | `#000` | Text color. |
| `--menu-item-font-size` | `14px` | Font size. |
| `--menu-item-font-weight` | `500` | Font weight. |
| `--menu-item-bg` | `initial` | Background. |
| `--menu-item-hover-clr` | `var(--primary-clr)` | Hover text color. |
| `--menu-item-hover-bg` | `initial` | Hover background. |
| `--menu-item-hover-border-bg` | `var(--menu-item-active-border-bg)` | Hover underline color. |
| `--menu-item-hover-border-height` | `var(--menu-item-active-border-height)` | Hover underline height. |
| `--menu-item-active-clr` | `var(--menu-item-hover-clr)` | Text color of the current page's item. |
| `--menu-item-active-bg` | `initial` | Background of the current page's item. |
| `--menu-item-active-border-bg` | `var(--primary-clr)` | Underline color of the current page's item. |
| `--menu-item-active-border-height` | `2px` | Underline height of the current page's item. |
| `--menu-item-inline-padding` | `1.1rem` | Left and right padding. |
| `--menu-item-block-padding` | `1rem` | Top and bottom padding. |
| `--menu-items-gap` | `0px` | Gap between items. Always add a unit. |
| `--menu-item-border` | `1px solid rgba(0, 0, 0, 0.1)` | Item border. |
| `--menu-item-radius` | `0` | Item corner radius. |
| `--menu-item-separator-color` | `silver` | Separator line color. |
| `--menu-item-separator-inset` | `20px` | Space above and below the separator line. |
| `--menu-item-separator-width` | `0px` | Separator line width. `0px` hides it. |
| `--chevron-size` | `14px` | Dropdown arrow size. |
| `--chevron-clr` | `var(--menu-item-clr)` | Dropdown arrow color. |
| `--chevron-hover-clr` | `var(--menu-item-hover-clr)` | Dropdown arrow hover color. |

To stop a link from getting the active style, add the class `dwc-exclude` to the link, or to an element that holds it.

### Multilevel dropdown links

| Variable | Default | What it does |
| --- | --- | --- |
| `--dropdown-item-clr` | `var(--menu-item-clr)` | Text color. |
| `--dropdown-item-font-size` | `var(--menu-item-font-size)` | Font size. |
| `--dropdown-item-bg` | `initial` | Background. |
| `--dropdown-indent-bg` | `rgb(0 0 0 / 5%)` | Background of a nested submenu that expands in place. |
| `--dropdown-heading-clr` | `var(--primary-clr)` | **Full.** A heading color for your own mega menu content. Use it with `color: var(--dropdown-heading-clr)`. |
| `--dropdown-item-hover-clr` | `var(--menu-item-hover-clr)` | Hover text color. |
| `--dropdown-item-hover-bg` | `white` | Hover background. |
| `--dropdown-expanded-clr` | `white` | Text color of an open parent item on mobile, when submenus expand. |
| `--dropdown-expanded-bg` | `black` | Background of an open parent item on mobile, when submenus expand. |
| `--dropdown-item-inline-padding` | `var(--menu-item-inline-padding)` | Left and right padding. |
| `--dropdown-item-block-padding` | `var(--menu-item-block-padding)` | Top and bottom padding. |
| `--dropdown-indent` | `0.6rem` | Indent of nested submenus. |
| `--dropdown-indent-item-pad-offset` | `0.5` | Extra padding for indented items. |
| `--dropdown-indent-line` | `solid 1px rgb(0 0 0 / 25%)` | Line beside indented submenus. |
| `--dropdown-item-radius` | `var(--dropdown-content-border-radius)` | Item corner radius. |
| `--dropdown-inactive-overlay` | `rgb(0 0 0 / 10%)` | Shade over a dropdown on desktop while one of its submenus is open. |

### CTA buttons

These apply when the last one, two or three menu items are buttons. See the `data-last-item-is-button` attribute on [Nav (Nestable)](elements/nav-nestable.md).

| Variable | Default | What it does |
| --- | --- | --- |
| `--cta-width` | `100%` | Maximum width of every CTA button on mobile. |
| `--cta-gap-offset` | `0` | Gap between two or three CTA buttons on mobile, offcanvas and sidebar. |
| `--cta-breakout-gap` | `20px` | Gap between a breakout CTA and the menu toggle on mobile. |

**Last button**

| Variable | Default | What it does |
| --- | --- | --- |
| `--menu-cta-clr` | `white` | Text color. |
| `--menu-cta-bg` | `black` | Background. |
| `--menu-cta-inline-padding` | `calc(var(--menu-item-inline-padding) * 1.3)` | Left and right padding. |
| `--menu-cta-block-padding` | `var(--menu-item-block-padding)` | Top and bottom padding. |
| `--menu-cta-border` | `none` | Border. |
| `--menu-cta-radius` | `0em` | Corner radius. |
| `--menu-cta-hover-clr` | `white` | Hover text color. |
| `--menu-cta-hover-bg` | `var(--primary-clr)` | Hover background. |

**Second and third button**

The second button uses the same variables with `-2-`, and the third with `-3-`. By default they copy the last button's padding, border and radius.

| Second button | Third button | Default |
| --- | --- | --- |
| `--menu-cta-2-clr` | `--menu-cta-3-clr` | `white` |
| `--menu-cta-2-bg` | `--menu-cta-3-bg` | `black` |
| `--menu-cta-2-inline-padding` | `--menu-cta-3-inline-padding` | `var(--menu-cta-inline-padding)` |
| `--menu-cta-2-block-padding` | `--menu-cta-3-block-padding` | `var(--menu-cta-block-padding)` |
| `--menu-cta-2-border` | `--menu-cta-3-border` | `var(--menu-cta-border)` |
| `--menu-cta-2-radius` | `--menu-cta-3-radius` | `var(--menu-cta-radius)` |
| `--menu-cta-2-hover-clr` | `--menu-cta-3-hover-clr` | `white` |
| `--menu-cta-2-hover-bg` | `--menu-cta-3-hover-bg` | `var(--primary-clr)` |

### Adaptive height and Stripe style

**Full only.** Turn these features on with the `adaptiveHeight` and `stripeStyle` options. See [Menu Options](menu-options.md).

| Variable | Default | What it does |
| --- | --- | --- |
| `--adaptive-height-bg` | `var(--dropdown-content-bg)` | Background of the adaptive height panel. Also the Stripe background. |
| `--adaptive-height-border` | `1px solid #fff` | Border of the adaptive height panel. |
| `--adaptive-height-shadow` | `0 0 30px rgb(39 50 59 / 10%)` | Shadow of the adaptive height panel. |
| `--stripe-border-radius` | `10px` | Stripe corner radius, when the `data-optimize-stripe` attribute is `true`. |

### Mobile and offcanvas menu

| Variable | Default | What it does |
| --- | --- | --- |
| `--mobile-menu-ttf` | `cubic-bezier(0.8, 0.07, 0.2, 0.95)` | Easing of the mobile menu animation. |

### Overlay header

These apply when the overlay header is on. See [Header Pro](elements/header-pro.md).

| Variable | Default | What it does |
| --- | --- | --- |
| `--overlay-header-width` | `1400px` | Width of the floating header. |
| `--overlay-header-inset` | `1rem` | Space between the floating header and the edges of the screen. |
| `--overlay-header-bg` | `rgb(255 255 255 / 100%)` | Background. Use an `rgba` color to make it see-through. |
| `--overlay-header-bg-active` | `rgb(255 255 255 / 100%)` | Background while a dropdown is open. |
| `--overlay-header-blur` | `10px` | Blur behind the header. |
| `--overlay-header-radius` | `1rem` | Corner radius. |
| `--overlay-header-shadow` | `0px 2px 20px rgb(0 0 0 / 20%)` | Shadow. |
| `--overlay-offset-padding` | `clamp(5rem, 1.875rem + 12.5vw, 11.25rem)` | Top padding added to the first section, when the `data-offset-section-padding` attribute is `true`. |

### Back text

The back text is the bar at the top of an open submenu on mobile.

| Variable | Default | What it does |
| --- | --- | --- |
| `--back-text-clr` | `var(--menu-item-clr)` | Text color. |
| `--back-text-font-size` | `12px` | Font size. |
| `--back-text-font-weight` | `600` | Font weight. |
| `--back-text-transform` | `uppercase` | Text case. |
| `--back-text-bg` | `var(--mobile-menu-topbar-bg)` | Background. |

### Sidebar navigation (overlay mode)

**Full only.** These apply when the `data-overlay-sidebar` attribute is `true`.

| Variable | Default | What it does |
| --- | --- | --- |
| `--overlay-sidebar-radius` | `1rem` | Corner radius. |
| `--overlay-sidebar-bg` | `rgb(255 255 255 / 80%)` | Background. |
| `--overlay-sidebar-shadow` | `0 0 30px rgb(39 50 59 / 10%)` | Shadow. |
| `--overlay-sidebar-inset` | `12px` | Space around the sidebar. |

### Don't edit

The code works these out from your other values. Leave them alone: `--open-icon-line-height`, `--icon-line-gap`, `--open-icon-line-variance`, `--iw`, `--aw`, `--caret-size`, `--dropdown-content-border`.

***

## Dropdown item as a button or icon

You can turn a **Dropdown** element's menu item into a button or an icon. Add the `data-is-button` or `data-is-icon` attribute to the Dropdown element. It needs no value.

* `data-is-button` gives the item a button style.
* `data-is-icon` shows only the icon. The text stays readable for screen readers.

Style them in the **DROPDOWN ITEM IS BUTTON OR ICON** section of the CSS tab. Both rules use the menu item variables above, plus these:

| Variable | Used by | What it does |
| --- | --- | --- |
| `--menu-item-width` | Button | Button width on mobile. |
| `--menu-item-hover-border` | Button, icon | Hover border. |
| `--icon-clr` | Icon | Icon color. |
| `--icon-hover-clr` | Icon | Icon hover color. |
| `--icon-size` | Icon | Icon size. |
| `--button-max-diameter` | Icon | Maximum size of the round icon button. |

***

## Sticky header with overlay special styles

This preset gives an overlay header a see-through look before you scroll, with white text and logo. Once you scroll, it switches to a solid background.

To turn it on:

1. Turn on the sticky header: in the builder, open **Settings > Template Settings > Header** and turn on **Sticky header**.
2. Set the `data-overlay-header` attribute on the **Header Pro** element to `true`.
3. Set the `data-sticky-overlay-special-style` attribute on the same element to `true`.

Change the look in the **STICKY HEADER WITH OVERLAY SPECIAL STYLES** section of the CSS tab. Each rule has a comment saying when it applies: before scrolling, while scrolling, or while a dropdown is open.

Two variables in this section control the speed of the change before you scroll:

| Variable | Default | What it does |
| --- | --- | --- |
| `--link-transition` | `0s` | Speed of link color changes. |
| `--transition` | `0.2s` | Speed of the header change. |

### Use the special styles on one page only

1. Edit the page with Bricks, then open **Settings > Page Settings > General**.
2. In **CSS classes (body)**, add `home-page`. You can use any class name, as long as you use the same one in step 4.
3. Open your header template, then the CSS tab of **MENU Styles / Options**.
4. In the **STICKY HEADER WITH OVERLAY SPECIAL STYLES** section, change every `.bricks-is-frontend` to `.bricks-is-frontend.home-page`.

Don't miss one, or that rule still applies on every page.
