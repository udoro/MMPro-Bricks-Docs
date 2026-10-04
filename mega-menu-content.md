---
icon: table-columns
---

# Mega Menu Content

A mega menu is a **Dropdown** element with Bricks' **Mega menu** setting turned on. Its panel holds any layout you like: columns, icons, text, images and buttons.

```
Dropdown                (Mega menu on)
└── Content             (the panel: width and position attributes go here)
    └── Content Inner   (your layout goes here)
```

***

## Build your own mega menu content

Don't put your content straight into the **Content** element. Put a **Content Inner** element in first, then build inside it.

The template's mega menus already have a Content Inner. You only need these steps for a mega menu that doesn't have one.

1. Select the mega menu's **Content** element.
2. Add a **Block** element inside it.
3. Set the block's HTML tag to `li`. If `li` isn't in the list, pick **Custom** and type `li`.
4. Rename the block to **Content Inner** in the Structure panel, so it's easy to find.
5. Build your layout inside this block.

Use Content Inner for layout, such as grid or flex. Layout styles on Content itself can break the mobile menu, mainly when submenus expand, and the panel loses its padding.

To start from a ready-made layout, use a [starter template](getting-started.md#starter-templates).

***

## Width

Mega Menu Pro picks a width in this order:

1. The `data-content-width` attribute on the dropdown's **Content** element.
2. The `data-global-content-width` attribute on the **Nav (Nestable)** element.
3. The `--dropdown-content-default-width` variable (`1120px`).

Both attributes take the same kinds of value:

| Value | Example | Result |
| --- | --- | --- |
| A number | `1080` or `1080px` | That width. Without a unit, it's pixels. Other units work too, such as `80vw`. |
| A CSS variable | `var(--content-width)` | The variable's value. |
| A selector | `#brx-header`, `.my-container`, `main` | The same width as that element. |

A mega menu is never wider than the screen.

### Make every mega menu full width

1. Select the **Nav (Nestable)** element and open **Style > Attributes**.
2. Set `data-global-content-width` to `#brx-header`.
3. Leave `data-content-width` empty on every mega menu's **Content** element. A value there wins over the global one.

***

## Position

### Horizontal

Set the `data-content-align` attribute on the dropdown's **Content** element.

| Value | Result |
| --- | --- |
| `left` | Lines up with the left edge of its menu item. |
| `right` | Lines up with the right edge of its menu item. |
| `center` | Centered under its menu item. |
| Empty | Lines up with the header. |

If a mega menu would run off the screen, it's moved back in. The `shiftFactor` and `minOverflow` options control this. See [Menu Options](menu-options.md).

### Vertical

Set the `data-global-content-vertical` attribute on the **Nav (Nestable)** element to a selector, such as `#brx-header`. The top of every mega menu and dropdown then sits at the bottom of that element.

Leave it empty to keep the default position.

### Gap and buffer

* `--dropdown-content-gap` sets a gap between the header and the mega menu.
* `--dropdown-buffer` adds an invisible area under the menu item, so the mouse can move to the mega menu at an angle without closing it. To see this area in the builder, set the `preview-buffer` attribute on **Nav (Nestable)** to `true`.

See [Styling](styling.md).

***

## Builder preview

These attributes only change what you see in the builder.

| Attribute | Element | What it does |
| --- | --- | --- |
| `builder-preview-content-width` | Nav (Nestable) | Width of mega menus in the builder, in pixels. |
| `preview-alignment` | Content | Set to `true` to see the `data-content-align` position in the builder. |
| `data-hide-instruction` | Content | Set to `true` to hide the instruction label in the builder. |
| `preview-buffer` | Nav (Nestable) | Set to `true` to see the `--dropdown-buffer` area in red. |

Don't change the `style` attribute on the **Content** element. Mega Menu Pro uses it.

***

## Older versions

**Version 1.3 and older** set the width with Bricks' own mega menu setting. It still works, and the Lite template still has the attribute for it, but use the attributes above for new menus.

1. Set the `data-use-selector` attribute on the dropdown's **Content** element to `true`.
2. Select the mega menu's **Dropdown** element.
3. In the Content panel, open the **Mega Menu** tab.
4. Paste your header's selector into **CSS Selector (Horizontal)**.

**Version 1.4** only took a number without `px`, or a selector, in `data-content-width` and `data-global-content-width`.
