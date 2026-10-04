---
icon: wand-magic-sparkles
---

# Adaptive Header Styling

Adaptive Header Styling changes the header's look to match the section under it. Over a dark hero, the menu turns white. Over a light section, it turns dark. It works on every screen size.

***

## Set it up

### 1. Add the code

1. In your Gumroad dashboard, find **Adaptive Header Styling** and copy the CSS and the JavaScript.
2. Open your header template in Bricks.
3. Add a **Code** element and paste in the CSS and the JavaScript.
4. Make sure the code runs. If Bricks asks you to sign it, sign it. See [Installation](../getting-started.md#turn-on-code-execution).

### 2. Turn on the sticky header

Turn on **Sticky header** in the header template's settings. Adaptive Header Styling needs it.

### 3. Check the header selector

The code looks for `.dwc-nest-header`, which is the Mega Menu Pro header. If you use a different header, change it at the top of the JavaScript:

```js
const HEADER_CONFIG = {
  headerElement: '.your-header-class',
  wrapperSelectors: [
    // '.your-wrapper-class'
  ],
  ...
};
```

Add selectors to `wrapperSelectors` for any wrappers inside the header that also need the new background.

### 4. Mark your sections

The header reads the background color of each section and picks light or dark text on its own. You don't need to mark anything for that.

To give a section its own header style, add one of these attributes to it. They need no value.

| Attribute | Section type | Class added to `<body>` |
| --- | --- | --- |
| `data-hero` | Hero | `on-hero-section` |
| `data-video` | Video | `on-video-section` |
| `data-media` | Media | `on-media-section` |
| `data-custom` | Custom | `on-custom-section` |
| `data-exclude` | The header doesn't change over this section | None |

The code watches `<section>` elements. If your section is a div, add the `data-header-zone` attribute to it.

### 5. Test

Scroll down the page. The header should change as it passes over each section.

***

## What it changes

As the header passes over a section, the code adds classes to `<body>`:

| Class | Added when |
| --- | --- |
| `on-dark-bg` | The section is dark, so the header needs light text. |
| `on-light-bg` | The section is light, so the header needs dark text. |
| `on-hero-section` | The section has `data-hero`. |
| `on-video-section` | The section has `data-video`. |
| `on-media-section` | The section has `data-media`. |
| `on-custom-section` | The section has `data-custom`. |

It also sets the `--current-header-bg` variable to the header's current background color. You can use it anywhere in your CSS.

***

## Customize the look

Set Mega Menu Pro variables inside these classes. The CSS you pasted already has examples.

```css
/* Dark sections */
.on-dark-bg {
  --primary-clr: orangered;
  --on-dark-txt-clr: white;
  --menu-item-clr: var(--on-dark-txt-clr);
  --chevron-clr: var(--on-dark-txt-clr);
  --dropdown-item-clr: var(--on-dark-txt-clr);
}

/* Light sections */
.on-light-bg {
  --menu-item-clr: black;
}

/* Video sections */
.on-video-section {
  --current-header-bg: rgb(0 0 0 / 14%) !important;
  --menu-item-clr: white;
  --dropdown-content-bg: rgb(0 0 0 / 70%);
  --mobile-menu-bg: rgb(0 0 0 / 95%);
}
```

### Match the dropdowns to the header

```css
.on-dark-bg,
.on-light-bg {
  --dropdown-content-bg: var(--current-header-bg);
  --tab-content-bg: var(--current-header-bg);
}
```

### More examples

```css
/* Blur behind the header over video */
.dwc-nest-header.on-video-section {
  backdrop-filter: blur(var(--overlay-header-blur));
}

/* A border over dark or video sections */
.dwc-nest-header:is(.on-dark-bg, .on-video-section) {
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* A white logo over dark or video sections */
.on-dark-bg .dwc-nest-menu__logo path,
.on-video-section .dwc-nest-menu__logo path {
  fill: white;
}

/* Your own mega menu content */
.on-dark-bg .mega-menu-magic__dropdown-title,
.on-video-section .mega-menu-magic__dropdown-title {
  color: rgb(210 210 210);
}
```

### Style the header over an excluded section

```css
[data-exclude] .dwc-nest-header {
  background: #your-color !important;
}
```

***

## Add a section type

Add a line to `SECTION_TYPES` in the JavaScript. The left side is the attribute you'll put on the section. The right side is the class added to `<body>`.

```js
const SECTION_TYPES = {
  'data-hero': 'on-hero-section',
  'data-video': 'on-video-section',
  'data-custom': 'on-custom-section',
  'data-media': 'on-media-section',
  'data-testimonials': 'on-testimonials-section'
};
```

Then style it with `.on-testimonials-section { ... }`.

***

## Troubleshooting

**The header doesn't change**

* Check `headerElement` in `HEADER_CONFIG` matches your header.
* Check the sticky header is on.
* Check the section has a background color.
* If the section is a div, add `data-header-zone` to it.
* Check the section doesn't have `data-exclude`.
* Check the attribute is spelled right.

**The header changes when it shouldn't**

* Add `data-exclude` to that section.
* Look for other CSS that sets the same variables.

