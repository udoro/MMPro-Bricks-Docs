---
icon: folder-tree
---

# Tabbed Navigation

Tabbed Navigation is a multi-level panel with tabs down the side. Hover or click a tab and its content shows next to it. It fits a lot of links into a small space.

Good for:

* WooCommerce categories (categories, subcategories, featured products)
* Services (services, sub-services, details)
* Team directories (departments, people, bios)
* Any content with several levels

This page covers version **1.3.1**.

***

## Add it to your header

### Option 1: Template element (recommended)

1. Import the Tabbed Navigation template in **Bricks > Templates**.
2. In your header template, select the mega menu's **Content** element.
3. Add a **Template** element inside it and pick the Tabbed Navigation template.
4. Turn on **Render without wrapper**. The menu's HTML stays valid with it on.

This keeps your header template tidy and gives you more room to edit the tabs.

### Option 2: Copy it in

Copy the whole Tabbed Navigation into the mega menu's **Content** element. This works, but the header gets crowded to edit.

### The code

The CSS and JavaScript are in the **Tabbed navigation code** code block.

* **JavaScript:** move it to your header template or a code manager, so it loads once even if you use more than one Tabbed Navigation.
* **CSS:** if you use more than one Tabbed Navigation, move it to a stylesheet so it isn't loaded twice.

Sign the code block after you move or change it. See [Installation](../getting-started.md#turn-on-code-execution).

***

## Structure

```
Tabbed Nav Container          (attributes go here)
└── Tabbed Nav Inner wrap
    ├── Tab List wrapper      (the sidebar)
    │   └── Tabbed nav ul
    │       └── tabbed nav li           (a tab: loop this for dynamic tabs)
    │           ├── button              (the tab)
    │           └── tabbed nav content  (what the tab shows)
    │               └── tabbed nav content inner
    │                   └── your links and layout
    └── Hero block            (optional, for the Tabbed Hero layout)
```

***

## Build a WooCommerce category menu

### First level: parent categories

1. Turn on the query loop on **tabbed nav li**.
2. Set the query to **Terms > Product categories**.
3. Set **Parent** to `0`, so it lists top-level categories.
4. Exclude `uncategorized`.
5. Turn on **Show empty** if you want categories with no products.
6. Set the button text to the term name.

### Second level: subcategories

1. Inside the tab's content, loop an `li`.
2. Set the query to **Terms > Product categories**.
3. Set **Parent** to the term ID from the parent loop.
4. Turn on **Show empty** if you need it.
5. Set the text to the term name.

### Third level and more

Repeat the second level. Keep it to three levels for speed.

### Mix in static content

Keep one **tabbed nav li** outside the loop and fill it by hand.

***

## Tabbed Hero layout

Tabbed Hero puts your own hero content next to the tabs.

* **Desktop:** tabs on the left, hero content on the right. The content area takes 70% of the width.
* **Mobile:** tabs on top, hero content below.

To set it up:

1. On **Tabbed Nav Container**, set the `data-tabbed-hero` attribute to `true`.
2. Inside **Tabbed Nav Inner wrap**, add a **Block** element next to **Tab List wrapper**. Use a Block, not a Section.
3. Put your hero content in the block and style it as usual.

```
Section
└── Container
    └── Tabbed Nav Container   (data-tabbed-hero="true")
        └── Tabbed Nav Inner wrap
            ├── Tab List wrapper
            └── Hero block     (you add this)
```

***

## Attributes

Set these on the **Tabbed Nav Container** element.

| Attribute | Values | What it does |
| --- | --- | --- |
| `data-nav-list-align` | `top`, `center`, `bottom` | Lines up the tabs at the top, middle or bottom of the sidebar. |
| `data-nav-list-fit-content` | `true`, `true-all` | Desktop. The sidebar is only as tall as its tabs. `true` applies to the first level, `true-all` to every level. Leave empty for a full-height sidebar. |
| `data-divider` | `true` | Desktop. Adds a line between the tabs and the content. |
| `data-bleed` | `true` | The active tab's background runs into the content area. |
| `data-content-reveal` | `wipe`, `fade-in` | Desktop. How the content appears. `wipe` wipes in from the left. `fade-in` fades in with a small slide. Leave empty for no animation. |
| `data-slide-in` | `true` | Mobile. Content slides in from the side instead of opening in place. The template has it on. |
| `data-back-text` | `auto` or your text | Mobile, with `data-slide-in`. Text of the back button. `auto` uses the tab's name. |
| `data-tabbed-hero` | `true` | Turns on the Tabbed Hero layout. |

### Examples

```html
<!-- Simple -->
data-nav-list-fit-content="true" data-back-text="auto"

<!-- More visual -->
data-divider="true" data-bleed="true" data-content-reveal="fade-in" data-nav-list-align="center"

<!-- App-like on mobile -->
data-slide-in="true" data-back-text="Back" data-nav-list-fit-content="true"

<!-- Tabbed Hero -->
data-tabbed-hero="true" data-nav-list-align="center" data-content-reveal="wipe"
```

***

## JavaScript options

These are at the top of the JS in the **Tabbed navigation code** code block.

| Option | Default | What it does |
| --- | --- | --- |
| `DESKTOP_BREAKPOINT` | `1201` | The screen width where the desktop layout starts. See [Breakpoint](#breakpoint). |
| `DEBOUNCE_DELAY` | `250` | How long to wait after a resize before updating, in milliseconds. |
| `OPEN_FIRST_TAB` | `true` | Opens the first tab when the page loads. |
| `CLOSE_TAB_ON_MOUSEOUT` | `false` | Closes the tab when the mouse leaves. |
| `TOGGLE_ON_CLICK` | `false` | `false` opens tabs on hover on desktop. `true` opens them on click. |
| `REINIT_ON_URL_CHANGE` | `true` | Starts again when the URL changes. Keep it on if you use page transitions. |
| `PREWARM_TIMEOUT_MS` | `1500` | How long to wait for fonts and images to load before a tab opens on hover, in milliseconds. |

The Tabbed Hero layout uses its own values, in `tabbedHeroConfig`:

| Option | Default |
| --- | --- |
| `OPEN_FIRST_TAB` | `false` |
| `CLOSE_TAB_ON_MOUSEOUT` | `true` |

### Breakpoint

Tabbed Navigation has its own breakpoint. Keep it the same as Mega Menu Pro's.

1. Set `DESKTOP_BREAKPOINT` to your desktop width.
2. In the CSS of the same code block, change `1201px` to your desktop width and `1200px` to your mobile width.

***

## CSS variables

These are at the top of the CSS in the **Tabbed navigation code** code block.

| Variable | Default | What it does |
| --- | --- | --- |
| `--tab-content-bg` | `var(--tab-list-item-active-bg)` | Content background. |
| `--tab-content-height` | `450px` | Maximum content height. Taller content scrolls. |
| `--tab-content-width` | `100%` | Content width. The Tabbed Hero layout sets it to `70%`. |
| `--tab-container-bg` | `var(--tab-content-bg)` | Background of the whole container. |
| `--tab-list-wrapper-bg` | `white` | Sidebar background. |
| `--tab-list-width` | `250px` | Sidebar width on desktop. |
| `--tab-list-inline-padding` | `initial` | Left and right padding of the sidebar. |
| `--tab-list-item-active-bg` | `white` | Background of the active or hovered tab. |
| `--tab-list-item-clr` | `black` | Tab text color. |
| `--tab-list-item-active-clr` | `black` | Text color of the active or hovered tab. |
| `--tab-list-item-radius` | `0px` | Tab corner radius. |
| `--tab-list-item-inline-padding` | `var(--menu-item-inline-padding, 12px)` | Left and right padding of each tab. |
| `--tab-list-item-block-padding` | `12px` | Top and bottom padding of each tab. |
| `--tab-arrow-clr` | `gray` | Arrow color. |
| `--tab-arrow-size` | `16px` | Arrow size. |
| `--tab-divider-inset` | `0px` | Space above and below the divider line. |
| `--tab-divider-width` | `1px` | Divider line width. |
| `--tab-divider-clr` | `rgb(0 0 0 / 20%)` | Divider line color. |
| `--bleed-bg` | `var(--tab-list-item-active-bg)` | Color that runs from the active tab into the content, with `data-bleed`. |
| `--bleed-height` | `44px` | Height of the bleed. Match it to your tab height. |

On mobile, `--tab-list-wrapper-bg`, `--tab-list-item-clr` and `--tab-arrow-clr` are reset in the `.dwc-mobile` rule below the variables. Change them there for mobile.

***

## How it behaves

**Desktop**

* Tabs open on hover, or on click if `TOGGLE_ON_CLICK` is `true`.
* The first tab opens on load, unless `OPEN_FIRST_TAB` is `false`.
* Content appears with the `data-content-reveal` animation.

**Mobile**

* Tabs open in place, like an accordion.
* With `data-slide-in="true"`, content slides in from the side, with a back button.

**Keyboard and screen readers**

* Arrow keys, Home, End, Enter and Space move between and open tabs.
* Tabs carry ARIA labels and states for screen readers.
* Animations respect the visitor's reduced-motion setting.

***

## Troubleshooting

**Content doesn't show**

* Check your loop settings. Turn on **Show empty** if your categories have no products yet.
* Top-level categories need **Parent** set to `0`.
* Make sure the **Tabbed navigation code** block is on the page and signed.

**Tabbed Hero looks wrong**

* `data-tabbed-hero="true"` must be on **Tabbed Nav Container**.
* The hero block must sit next to **Tab List wrapper**, inside **Tabbed Nav Inner wrap**.

**An attribute does nothing**

* It must be on **Tabbed Nav Container**, not on an inner element.
* Check the spelling of the attribute and its value.

**Mobile slide-in or back button doesn't work**

* Check `data-slide-in` is `true`.
* `data-back-text` only applies when `data-slide-in` is `true`.
* Check `DESKTOP_BREAKPOINT` matches your breakpoint.
