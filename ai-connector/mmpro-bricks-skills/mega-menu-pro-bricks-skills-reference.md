---
icon: sparkles
---

# AI Skills Lookup Tables

Lookup tables for Mega Menu Pro + Header Builder for Bricks, version **1.4.6** (1.4.5 has the same
attributes, options and variables), and its add-ons.
**Never read this file in full.** Grep the section or the name you need.

Values come from the shipped template files. On a site, the live element is authoritative: check
section 10 (Fingerprint) first if the version label is not 1.4.5 or 1.4.6.

***

## 1. Element map

Labels and classes as shipped. Users can rename labels; classes are global classes (IDs are
site-local, names are not).

| Element | `name` | Label | Global class | Tag | Notes |
| --- | --- | --- | --- | --- | --- |
| Header Pro | `section` | `Header Pro \| v1.4.6` (Lite: `... Lite`) | `dwc-nest-header` | `div` | Root. Header attributes (section 2). |
| Container | `container` | | `dwc-nest-header__container` | | Child of Header Pro. |
| Logowrap (link) | `div` | `Logowrap (link)` | `dwc-nest-menu__logo` | `a` | Desktop logo. Has `data-breakout-link`. |
| Menu wrap | `div` | `Menu wrap` | `dwc-menu-wrap` | | |
| Nav (Nestable) | `nav-nested` | | `dwc-nest-menu` | | Nav attributes (section 3). `mobileMenu: "never"`. |
| Toggle (Open: Mobile) | `toggle` | `Toggle (Open: Mobile)` | `dwc-nest-toggle--open` | | `toggleSelector: ".brxe-nav-nested"`, `animation: "vortex"`. |
| nav wrapper | `block` | `nav wrapper` | `dwc-nav-wrapper` | | The mobile panel. |
| mobile top bar | `block` | `mobile top bar` | `dwc-nest-menu-top` | | Holds mobile logo and close toggle. |
| mobile Logowrap | `div` | `mobile Logowrap` | `dwc-nest-menu__mobile-logo` | `a` | Empty uses the desktop logo. Never delete. |
| Toggle (Close: Mobile) | `toggle` | `Toggle (Close: Mobile)` | | | |
| Nav items | `block` | `Nav items` | `dwc-nest-nav-items` | `ul` | Parent of every top-level item. |
| Top-level link | `text-link` | `Link Item` | `dwc-nest-nav-top-link` | | `text`, `link`. |
| Dropdown | `dropdown` | | `dwc-nest-nav-top-link` | | `toggleOn: "both"`. Mega menu: `megaMenu: true`. |
| Multilevel Content | `div` | `Content` | `dwc-nest-dropdown-content` | `ul` | `_hidden: { _cssClasses: "brx-dropdown-content" }`. |
| Mega menu Content | `div` | `Content` | `dwc-nest-nav-list` | `ul` | Same `_hidden`. Content attributes (section 4). |
| Content Inner | `block` | `Content Inner ...` | starter template's own | `li` | Layout container. |
| Code blocks | `code` | see below | | | Root-level siblings of Header Pro. |

| Code block | Label | Edit | Identify by |
| --- | --- | --- | --- |
| MENU Styles / Options | `MENU Styles / Options` | Yes | `javascriptCode` contains `const MegaMenuCONFIG` |
| MEDIA QUERY | `MEDIA QUERY (don't edit or remove)` (Lite: `MENU Media Query (don't edit)`) | Breakpoint only | Full: `_attributes` has `data-stylesheet` |
| MEGA MENU Codes | `MEGA MENU Codes (don't edit)` | Breakpoint only | CSS starts `@media (min-width: 1201px)` |
| SIDEBAR Codes | `SIDEBAR Codes (don't edit)` | No | Full only |

Starter template classes in the shipped header (Nova layout): `mega-menu-nova__content-inner`,
`mega-menu-nova__content-group`, `mega-menu-nova__dropdown-title`, `mega-menu-nova__txt-wrap`,
`mega-menu-nova__txt`, `mega-menu-nova__btn`, `mega-menu-nova__nav-group`,
`mega-menu-nova__title-icon`, `mega-menu-nova__nav-wrap`, `mega-menu-nav-list`, `mega-menu-nova__li`,
`mega-menu-nova__link`, `mega-menu-quantum__txt-wrap`.

***

## 2. Header Pro attributes

Empty = off. Template value is empty for all.

| Attribute | Values | Effect |
| --- | --- | --- |
| `data-overlay-header` | `true` | Header floats over the page. `--overlay-header-*` vars. Page opt-out: `data-no-overlay` on the page's first section. |
| `data-overlay-header-mobile` | `true` | Overlay header on mobile too. |
| `data-allow-overlay-mobile-opacity` | `true` | Overlay transparency on mobile. With `data-slide-in-direction="top"` + `data-match-overlay-header-width`, the menu then appears from behind the header. |
| `data-overlay-header-optimize-adaptive-height` | `true` | Full. Adaptive height look on an overlay header. |
| `data-overlay-header-no-top-gap` | `true` | No gap above the overlay header; square top corners. |
| `data-fullscreen-mobile-menu` | `true` | Mobile menu set up for a multi-row header; adds the logo. |
| `data-hide-mobile-logo` | `true` | Hides that added logo. |
| `data-offset-section-padding` | `true` | Top padding on the first section (`--overlay-offset-padding`). Page opt-out: `data-no-overlay-offset-padding`. |
| `data-fix-centered-logo-fouc` | `true` | Stops the jump on load with a centered logo. |
| `data-sticky-overlay-special-style` | `true` | Sticky + overlay preset styles. Needs Bricks sticky header on and `data-overlay-header="true"`. |
| `data-mobile-special-style` | `slide-split` | Tablet reveal preset. |

Page-level, presence only, on the first section or div of a page: `data-no-overlay`,
`data-no-overlay-offset-padding`.

***

## 3. Nav (Nestable) attributes

| Attribute | Template value | Values | Effect |
| --- | --- | --- | --- |
| `data-offcanvas` | | `true` | Full. Offcanvas on desktop. |
| `data-slide-in-direction` | | `left`, `top`, `bottom`, `left top`, `left bottom` | Empty = right. `top` = right top, `bottom` = right bottom. |
| `data-submenu-reveal` | `slide` | `slide`, `expand` | Mobile/offcanvas/sidebar submenus. A Dropdown's own value overrides. |
| `data-match-overlay-header-width` | `true` | `true` | Only with `data-slide-in-direction="top"`: menu matches the header width and grows down. |
| `data-below-header` | | `true` | Menu opens below the header. |
| `data-last-item-is-button` | `true` | `true`, `true-2`, `true-3` | Last 1, 2 or 3 items are CTA buttons. Not on Dropdowns. |
| `data-last-item-is-button-alignment` | | `left`, `center` | Empty = right. |
| `data-hide-overlay` | | `true` | Hides the page overlay while a dropdown is open. |
| `data-overlay-on-header` | `true` | `true` | Full. Page overlay starts at the top of the screen, not below the header. |
| `data-back-text` | `auto` | `auto` or any text | Back bar text. `auto` = item name. |
| `data-tooltip-back-text` | `Swipe > back to` | any text | Full. Swipe hint. Needs `swipeToClose` and `toolTip`. |
| `data-hide-close-bar` | | `true` | Hides the back bar. |
| `data-show-mobile-logo` | | `true` | Shows the mobile logo. |
| `data-mobile-top-transparent` | `true` | `true` | Back bar over the header with the logo until a submenu opens. |
| `data-overlay-sidebar` | | `true` | Full. Sidebar floats over the page. `--overlay-sidebar-*` vars. |
| `data-sidebar-back-text-on-logo` | | `true` | Full. Back bar aligned to the header top in sidebar mode. |
| `data-align-content-bottom` | `true` | `true` | Desktop. Items fill header height; dropdowns start at the header bottom. Needed by `data-caret`. |
| `data-optimize-stripe` | `true` | `true` | Full. Stripe style look. |
| `data-caret` | | `true` | Dropdown arrow. Needs `data-align-content-bottom="true"` and `--dropdown-content-gap` > `0px`. |
| `data-show-toggle-always` | `true` | `true` | Toggle stays visible while a submenu is open (with `slide`). |
| `data-global-content-vertical` | | selector | Dropdown tops align to the bottom of that element. Empty + `stripeStyle` = `#brx-header`. |
| `data-global-content-width` | `1080` | number, unit value, `var(--x)`, selector | Width of every mega menu. |
| `builder-preview-content-width` | | number | Builder only. Mega menu width in the builder. |
| `data-align-dropdown-top` | `true` | `true` | Nested multilevel submenus align to the dropdown top. |
| `data-single-back-button` | `true` | `true` | One back button when Tabbed Navigation is inside a mega menu on mobile. |
| `preview-buffer` | | `true` | Builder only. Shows the `--dropdown-buffer` area in red. |
| `style` | Lite only | do not change | `--dropdown-content-width: attr(data-global-content-width px)` |

***

## 4. Dropdown and mega menu Content

**Dropdown element**

| Key | Values | Effect |
| --- | --- | --- |
| `settings.megaMenu` | `true` | Bricks: makes it a mega menu. |
| `settings.toggleOn` | `both` (template), `hover`, `click` | Bricks: open trigger. |
| `data-submenu-reveal` | `slide`, `expand`, empty | Overrides the Nav value for this dropdown. |
| `data-is-button` | presence | Button style. Rule `[data-is-button]>.brx-submenu-toggle` in MENU Styles / Options. |
| `data-is-icon` | presence | Icon only, text kept for screen readers. Rule `[data-is-icon]>.brx-submenu-toggle`. |

**Mega menu Content element** (template has all five)

| Attribute | Values | Effect |
| --- | --- | --- |
| `data-content-width` | number, unit value, `var(--x)`, selector | Beats `data-global-content-width`. Empty = use global. |
| `data-content-align` | `left`, `right`, `center` | Relative to the menu item. Empty = header. |
| `preview-alignment` | `true` | Builder only. |
| `data-hide-instruction` | `true` | Builder only. Hides the instruction label. |
| `style` | copy exactly | `--dropdown-content-width: attr(data-content-width px, var(--global-width));` |

Width order: Content `data-content-width` > Nav `data-global-content-width` >
`--dropdown-content-default-width`. A number with no unit is px. A selector copies that element's
width. Capped at the viewport width.

***

## 5. CSS variables (MENU Styles / Options CSS)

Grep the variable. `RULE` is the selector it sits in: edit it there. `[Full]` = not in Lite.
Comments are the template's own. To set a mobile-only or sticky-only value, add the variable to the
`html.dwc-mobile` or `.brx-sticky.scrolling` rule; do not change the `:root` value.

`--chevron-color` (in `[data-is-button]`) is read by nothing; `--chevron-clr` is the real one. The
"do not edit" variables at the end of `:root` are derived: never write them.

```
RULE html.dwc-mobile
  --mobile-menu-width: min(450px, 100%)
  --menu-item-font-size: 18px
  --dropdown-item-font-size: var(--menu-item-font-size)
  --back-text-font-size: 16px
  --menu-item-hover-border-bg: initial

RULE :root
  # GENERAL COLORS/BACKGROUND
  --primary-clr: orangered
  --header-bg: white
  --dropdown-content-bg: white
  --mobile-menu-bg: var(--header-bg)
  --mobile-menu-topbar-bg: var(--header-bg)
  # GENERAL WIDTH | HEIGHT | SPACINGS
  --mobile-menu-width: min(300px, 100%)  // mobile & offcanvas
  --mobile-logo-height: 50px  [Full]  // when mobile menu is in full screen mode
  --multilevel-dropdown-width: 200px
  --dropdown-content-gap: 0px  // gap between header and dropdown, add unit (e.g. 0px)
  --header-min-height: 60px
  --fullscreen-mobile-menu-top-height: 60px
  --top-offset: 40px  // when nav is below header
  --dropdown-content-default-width: 1120px  // preview width & default width for dropdown content
  --header-inline-padding: clamp(1.5rem, calc(0.625vw + 1.375rem), 1.875rem)
  --dropdown-buffer: inherit  // sets a buffer value in px to allow moving mouse pointer accross an angle to the dropdown content without closing it
  # GENERAL BORDERS | SHADOWS | OVERLAY BACKDROP
  --dropdown-content-border-radius: 0px
  --dropdown-content-shadow: 0px 5px 15px -10px rgb(0 0 0 / 0%)
  --dropdown-content-border-size: 1px  // at least 1px
  --dropdown-content-border-color: var(--dropdown-content-bg)
  --nav-overlay-backdrop-blur: 0px
  --nav-overlay-backdrop-clr: rgba(0, 0, 0, 0.3)
  --sidebar-shadow: 0px 0px 2px rgba(0, 0, 0, 0.7)  [Full]
  --mobile-menu-radius: var(--overlay-header-radius)
  --slide-out-speed: 1.3
  --menu-toggle-clr: var(--menu-item-clr)
  --menu-close-toggle-clr: var(--menu-item-clr)
  --menu-toggle-hover-clr: var(--menu-item-hover-clr)
  --open-icon-size: 40px  // menu toggle size
  # MENU ITEMS
  --menu-item-clr: #000
  --menu-item-font-size: 14px
  --menu-item-font-weight: 500
  --menu-item-bg: initial
  --menu-item-hover-clr: var(--primary-clr)
  --menu-item-hover-bg: initial
  --menu-item-hover-border-bg: var(--menu-item-active-border-bg)
  --menu-item-hover-border-height: var(--menu-item-active-border-height)
  --menu-item-active-clr: var(--menu-item-hover-clr)
  --menu-item-active-bg: initial
  --menu-item-active-border-bg: var(--primary-clr)
  --menu-item-active-border-height: 2px
  --menu-item-inline-padding: 1.1rem
  --menu-item-block-padding: 1rem
  --menu-items-gap: 0px  // always add unit (e.g. 0px)
  --menu-item-border: 1px solid rgba(0, 0, 0, 0.1)
  --menu-item-radius: 0
  --menu-item-separator-color: silver
  --menu-item-separator-inset: 20px
  --menu-item-separator-width: 0px
  --chevron-size: 14px
  --chevron-clr: var(--menu-item-clr)
  --chevron-hover-clr: var(--menu-item-hover-clr)
  # MULTILEVEL DROPDOWN LINKS
  --dropdown-item-clr: var(--menu-item-clr)
  --dropdown-item-font-size: var(--menu-item-font-size)
  --dropdown-item-bg: initial
  --dropdown-indent-bg: rgb(0 0 0 / 5%)
  --dropdown-heading-clr: var(--primary-clr)  [Full]
  --dropdown-item-hover-clr: var(--menu-item-hover-clr)
  --dropdown-item-hover-bg: white
  --dropdown-expanded-clr: white
  --dropdown-expanded-bg: black
  --dropdown-item-inline-padding: var(--menu-item-inline-padding)
  --dropdown-item-block-padding: var(--menu-item-block-padding)
  --dropdown-indent: 0.6rem
  --dropdown-indent-item-pad-offset: 0.5
  --dropdown-indent-line: solid 1px rgb(0 0 0 / 25%)
  --dropdown-item-radius: var(--dropdown-content-border-radius)
  --dropdown-inactive-overlay: rgb(0 0 0 / 10%)
  # MENU CTA BUTTON (ALL BUTTONS)
  --cta-width: 100%
  --cta-gap-offset: 0
  --cta-breakout-gap: 20px
  # MENU CTA BUTTON (LAST BUTTON)
  --menu-cta-clr: white
  --menu-cta-bg: black
  --menu-cta-inline-padding: calc(var(--menu-item-inline-padding) * 1.3)
  --menu-cta-block-padding: var(--menu-item-block-padding)
  --menu-cta-border: none
  --menu-cta-radius: 0em
  --menu-cta-hover-clr: white
  --menu-cta-hover-bg: var(--primary-clr)
  # MENU CTA BUTTON (SECOND BUTTON)
  --menu-cta-2-clr: white
  --menu-cta-2-bg: black
  --menu-cta-2-inline-padding: var(--menu-cta-inline-padding)
  --menu-cta-2-block-padding: var(--menu-cta-block-padding)
  --menu-cta-2-border: var(--menu-cta-border)
  --menu-cta-2-radius: var(--menu-cta-radius)
  --menu-cta-2-hover-clr: white
  --menu-cta-2-hover-bg: var(--primary-clr)
  # MENU CTA BUTTON (THIRD BUTTON)
  --menu-cta-3-clr: white
  --menu-cta-3-bg: black
  --menu-cta-3-inline-padding: var(--menu-cta-inline-padding)
  --menu-cta-3-block-padding: var(--menu-cta-block-padding)
  --menu-cta-3-border: var(--menu-cta-border)
  --menu-cta-3-radius: var(--menu-cta-radius)
  --menu-cta-3-hover-clr: white
  --menu-cta-3-hover-bg: var(--primary-clr)
  --open-icon-align: 0  // 0 = right | auto = left
  --open-icon-horizontal-offset: 0px  // nudge icon left or right from edge of screen
  --open-icon-close-offset: 1.2  // nudge icon left or right when close icon is active
  # ADAPTIVE HEIGHT/ STRIPE BG COLOR/BORDER
  --adaptive-height-bg: var(--dropdown-content-bg)  [Full]  // also controls stripe background
  --adaptive-height-border: 1px solid #fff  [Full]
  --adaptive-height-shadow: 0 0 30px rgb(39 50 59 / 10%)  [Full]
  --stripe-border-radius: 10px  [Full]
  # MOBILE/OFFCANVAS MENU
  --mobile-menu-ttf: cubic-bezier(0.8, 0.07, 0.2, 0.95)
  # OVERLAY HEADER
  --overlay-header-width: 1400px
  --overlay-header-inset: 1rem
  --overlay-header-bg: rgb(255 255 255 / 100%)
  --overlay-header-bg-active: rgb(255 255 255 / 100%)
  --overlay-header-blur: 10px
  --overlay-header-radius: 1rem
  --overlay-header-shadow: 0px 2px 20px rgb(0 0 0 / 20%)
  --overlay-offset-padding: clamp(5rem, 1.875rem + 12.5vw, 11.25rem)
  # BACK TEXT
  --back-text-clr: var(--menu-item-clr)
  --back-text-font-size: 12px
  --back-text-font-weight: 600
  --back-text-transform: uppercase
  --back-text-bg: var(--mobile-menu-topbar-bg)
  # SIDEBAR NAV - OVERLAY MODE
  --overlay-sidebar-radius: 1rem  [Full]
  --overlay-sidebar-bg: rgb(255 255 255 / 80%)  [Full]
  --overlay-sidebar-shadow: 0 0 30px rgb(39 50 59 / 10%)  [Full]
  --overlay-sidebar-inset: 12px  [Full]
  --open-icon-line-height: calc(var(--open-icon-size) * 0.1)
  --icon-line-gap: calc(var(--open-icon-size) * 0.25)
  --open-icon-line-variance: calc(var(--open-icon-size) * 0.25)
  --iw: calc(var(--open-icon-size) - var(--open-icon-line-variance))
  --aw: calc(var(--iw) - var(--open-icon-line-variance))
  --caret-size: calc(var(--dropdown-content-gap) + var(--dropdown-content-border-size))
  --dropdown-content-border: solid var(--dropdown-content-border-color) var(--dropdown-content-border-size)

RULE #brx-header:has([data-sidebar-back-text-on-logo="true"])
  --top-offset: var(--mobile-menu-top-height)

RULE [data-is-button]>.brx-submenu-toggle
  --menu-item-bg: black
  --menu-item-clr: white
  --menu-item-hover-clr: white
  --menu-item-hover-bg: black
  --menu-item-radius: 50vw
  --menu-item-hover-border-height: 0
  --chevron-size: 0
  --chevron-color: white
  --menu-item-inline-padding: 1.5rem
  --menu-item-block-padding: 1rem
  --menu-item-width: 200px
  --menu-item-border: solid 1px transparent
  --menu-item-hover-border: solid 1px transparent

RULE [data-is-icon]>.brx-submenu-toggle
  --menu-item-bg: black
  --menu-item-clr: white
  --menu-item-hover-clr: white
  --menu-item-hover-bg: black
  --icon-clr: white
  --icon-hover-clr: white
  --icon-size: 14px
  --menu-item-inline-padding: 1.1rem
  --button-max-diameter: 45px
  --menu-item-radius: 50vw
  --menu-item-border: solid 1px transparent
  --menu-item-hover-border: solid 1px transparent

RULE html:not(.dwc-mobile):has([data-sticky-overlay-special-style="true"]) .bricks-is-frontend .brx-sticky:not(.scrolling) .dwc-nest-header:not(:has(.brxe-dropdown.open, .brx-nav-nested-items > li:hover))
  --menu-item-clr: white
  --chevron-clr: white
  --menu-item-hover-clr: white
  --menu-item-hover-border-bg: white
  --overlay-header-bg: transparent
  --header-bg: transparent
  --overlay-header-shadow: none

RULE html:not(.dwc-mobile):has([data-sticky-overlay-special-style="true"]) .bricks-is-frontend .brx-sticky:not(.scrolling)
  --link-transition: 0s
  --transition: 0.2s

RULE html:not(.dwc-mobile):has([data-sticky-overlay-special-style="true"]) .bricks-is-frontend .brx-sticky.scrolling
  --overlay-header-bg: white
  --header-bg: white

RULE html:not(.dwc-mobile):has([data-sticky-overlay-special-style="true"]) .bricks-is-frontend .brx-sticky
  --menu-item-hover-bg: initial
  --menu-item-bg: initial
  --overlay-header-inset: 0
  --overlay-header-width: 100%
  --overlay-header-radius: 0
```

Empty rule for sticky values (no variables shipped):

```
RULE .brx-sticky.scrolling
```

***

## 6. JS options (MENU Styles / Options JS)

`MegaMenuCONFIG`:

| Option | Default | Effect |
| --- | --- | --- |
| `minWidth` | `1201` | Desktop starts here. Breakpoint change touches 3 blocks (build file). |
| `menuAutoExpansion` | `true` | Full. Opens the current page's dropdown on load (mobile, offcanvas, sidebar). |
| `swipeToClose` | `true` | Full. Swipe back/close in the mobile menu. |
| `toolTip` | `true` | Full. Swipe hint. Text from `data-tooltip-back-text`. |
| `adaptiveHeight` | `0` | Full. Smooth height between mega menus. |
| `stripeStyle` | `0` | Full. Morphing panel. Wins over `adaptiveHeight`. |
| `headerSelector` | `'#brx-header'` | Header element. |
| `nestMenuSelector` | `'.dwc-nest-menu'` | Do not change. |
| `closeNavOnClick` | `1` | Close the menu on link click. |
| `closeOnHashClickOnly` | `0` | `1` = only `#` links close it. |
| `closeOnMobileOnly` | `0` | `1` = close on click only on mobile. |
| `closeNavOnClickExclude` | `'.js-wpml-ls-item-toggle'` | Comma-separated selectors that never close it. |
| `breakinToNavList` | `1` | `1` = breakin container in the item list, `0` = end of nav wrapper. |
| `shiftFactor` | `1` | Shift amount for an off-screen dropdown. |
| `minOverflow` | `10` | px overflow before shifting. |
| `reinitializeOnURLchange` | `true` | Restart on URL change (page transitions). |
| `overlayInsideHeader` | `0` | `1` = page overlay also covers the header. Not with `data-overlay-header`. |

`CenteredLogoCONFIG`:

| Option | Default | Effect |
| --- | --- | --- |
| `enable` | `0` | `1` = logo centered among the menu items on desktop. |
| `centerGuide` | `1` | Guide for logged-in users. |
| `forceCenteredLogo` | `1` | Shift the menu so the logo is at the exact screen center. |
| `centerNudge` | `0` | px; negative left, positive right. |
| `allowOddItems` | `1` | Allow with an odd item count. |
| `roundOffFactor` | `'before'` | `'before'` or `'after'` the middle item. |

Lite has only `minWidth`, `headerSelector`, `nestMenuSelector`, the `close*` options,
`breakinToNavList`, `shiftFactor`, `minOverflow`, `reinitializeOnURLchange`, `overlayInsideHeader`,
and all of `CenteredLogoCONFIG`.

***

## 7. Offcanvas, sidebar and moving elements

**Offcanvas (Full):** Nav `data-offcanvas="true"`. Never raise the breakpoint instead.

**Sidebar (Full):** template settings > Header > Header Location left or right (user does this in the
builder). Then in MENU Styles / Options CSS, replace every `postid-23338` with `postid-<header
template ID>` so the menu shows in the builder.

**Editing offcanvas/sidebar in the builder:** the user turns on Nav (Nestable) > Mobile Menu > Keep
open while styling. Builder-only; you cannot check it.

**Moving elements** (presence attributes on the element that moves):

| Attribute | Values | Effect |
| --- | --- | --- |
| `data-breakout-link` | empty, or a width like `560` | Plain link only. Below the breakpoint it moves from the mobile menu into the header; below the width it goes back. Template: Logowrap (empty), last Link Item (`520`). |
| `data-breakin` | empty, a width like `767`, or `end` | Header element moves into the mobile menu (`.breakin-container`). `end` pushes the container to the bottom. |
| `data-breakinto` | `.selector` or `.selector \| 767` | Moves into any container below the breakpoint or the width. |

***

## 8. Tabbed Navigation (v1.3.1)

Separate template (type `section`). Insert in a mega menu's Content with a Template element,
"Render without wrapper" on, or copy it in.

```
block  "Tabbed Nav Container v1.3"  .dwc-tabbed-nav-container  tag li   <- attributes
code   "Tabbed navigation code"     CSS + JS
└ block "Tabbed Nav Inner wrap"     .dwc-tabbed-nav-inner-wrap
  ├ block "Tab List wrapper"        .dwc-tabbed-nav__list-wrapper
  │ └ block "Tabbed nav ul"         .dwc-tabbed-nav-list  tag ul
  │   └ block "tabbed nav li"       .dwc-tabbed-nav-list__li  tag li   <- loop here
  │     ├ div "button"              .dwc-tabbed-nav-list__li__button  (custom tag)
  │     │ ├ text-basic              .dwc-tabbed-nav-list__li__btn-txt  tag span
  │     │ └ icon                    .dwc-tabbed-nav-list__li__arrow-icon
  │     └ block "tabbed nav content"   .dwc-tabbed-nav-list__li__content
  │       └ block "tabbed nav content inner"  .dwc-tabbed-nav-list__li__content__inner
  └ block (hero, optional, user adds) sibling of Tab List wrapper, with data-tabbed-hero="true"
```

| Attribute (container) | Template value | Values | Effect |
| --- | --- | --- | --- |
| `data-nav-list-align` | | `top`, `center`, `bottom` | Tabs position in the sidebar. |
| `data-nav-list-fit-content` | | `true`, `true-all` | Desktop. Sidebar height fits tabs: first level, or all levels. |
| `data-slide-in` | `true` | `true` | Mobile slide-in content with back button. |
| `data-divider` | | `true` | Desktop divider line. |
| `data-bleed` | | `true` | Active tab background runs into content. |
| `data-back-text` | `auto` | `auto` or text | Mobile back button text, with `data-slide-in`. |
| `data-tabbed-hero` | | `true` | Hero layout, content 70% width. |
| `data-content-reveal` | | `wipe`, `fade-in` | Desktop reveal. Empty = none. |
| `element-outline` | `true` | | Read by no code. Leave it. |

`TabbedNavConfig`: `DESKTOP_BREAKPOINT: 1201`, `DEBOUNCE_DELAY: 250`, `OPEN_FIRST_TAB: true`,
`CLOSE_TAB_ON_MOUSEOUT: false`, `TOGGLE_ON_CLICK: false` (false = hover), `REINIT_ON_URL_CHANGE:
true`, `PREWARM_TIMEOUT_MS: 1500`. `tabbedHeroConfig` overrides `OPEN_FIRST_TAB: false`,
`CLOSE_TAB_ON_MOUSEOUT: true`. Breakpoint: also `1201px`/`1200px` in its CSS.

Variables (first `:root` in its CSS): `--tab-content-bg`, `--tab-content-height` (450px),
`--tab-content-width` (100%, hero 70%), `--tab-container-bg`, `--tab-list-wrapper-bg` (white),
`--tab-list-width` (250px), `--tab-list-inline-padding`, `--tab-list-item-active-bg` (white),
`--tab-list-item-clr`, `--tab-list-item-active-clr`, `--tab-list-item-radius`,
`--tab-list-item-inline-padding`, `--tab-list-item-block-padding`, `--tab-arrow-clr`,
`--tab-arrow-size`, `--tab-divider-inset`, `--tab-divider-width`, `--tab-divider-clr`, `--bleed-bg`,
`--bleed-height` (44px). Mobile resets in `.dwc-mobile { }` right after.

`data-tabbed-dropdown` appears on one Lite Dropdown and is read by no code. Leave it.

***

## 9. Adaptive Header Styling

One Code element in the header template with the add-on's CSS and JS. Needs Bricks sticky header.

* `HEADER_CONFIG.headerElement`: `'.dwc-nest-header'`. `wrapperSelectors`: extra wrappers.
* Watches `section, [data-header-zone]`. Add `data-header-zone` to a div that acts as a section.
* Section attributes (presence): `data-hero`, `data-video`, `data-media`, `data-custom`,
  `data-exclude`. New types: add to `SECTION_TYPES` (`'data-x': 'on-x-section'`).
* Adds to `<body>`: `on-dark-bg`, `on-light-bg`, `on-hero-section`, `on-video-section`,
  `on-media-section`, `on-custom-section`. Sets `--current-header-bg` on `<body>`.
* Style by setting MMPro variables inside those classes.

***

## 10. Fingerprint

Compare the live attribute **names** (not values) with these lists. All match: the tables above are
exact for this site. Any difference: trust the live element for that element.

**Header Pro, Full (11):** `data-overlay-header`, `data-overlay-header-mobile`,
`data-allow-overlay-mobile-opacity`, `data-overlay-header-optimize-adaptive-height`,
`data-overlay-header-no-top-gap`, `data-fullscreen-mobile-menu`, `data-hide-mobile-logo`,
`data-offset-section-padding`, `data-fix-centered-logo-fouc`, `data-sticky-overlay-special-style`,
`data-mobile-special-style`. **Lite (10):** the same without
`data-overlay-header-optimize-adaptive-height`.

**Nav (Nestable), Full (26):** `data-offcanvas`, `data-slide-in-direction`, `data-submenu-reveal`,
`data-match-overlay-header-width`, `data-below-header`, `data-last-item-is-button`,
`data-last-item-is-button-alignment`, `data-hide-overlay`, `data-overlay-on-header`,
`data-back-text`, `data-tooltip-back-text`, `data-hide-close-bar`, `data-show-mobile-logo`,
`data-mobile-top-transparent`, `data-overlay-sidebar`, `data-sidebar-back-text-on-logo`,
`data-align-content-bottom`, `data-optimize-stripe`, `data-caret`, `data-show-toggle-always`,
`data-global-content-vertical`, `data-global-content-width`, `builder-preview-content-width`,
`data-align-dropdown-top`, `data-single-back-button`, `preview-buffer`. **Lite (21):** without
`data-offcanvas`, `data-overlay-on-header`, `data-tooltip-back-text`, `data-overlay-sidebar`,
`data-sidebar-back-text-on-logo`, `data-optimize-stripe`; plus `style`.

**Mega menu Content, Full (5):** `data-content-width`, `data-content-align`, `preview-alignment`,
`style`, `data-hide-instruction`. **Lite (6):** plus `data-use-selector` (old width method: `true`
hands width to Bricks' own mega menu selector. Leave it empty).
