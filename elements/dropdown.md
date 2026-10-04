---
icon: square-caret-down
---

# Dropdown

Each **Dropdown** element is a top-level menu item that opens something. It's either a multilevel dropdown (a list of links) or a mega menu (any layout).

```
Nav items
├── Dropdown            multilevel dropdown
│   └── Content         (class dwc-nest-dropdown-content)
│       ├── Nav link
│       └── Dropdown    a nested submenu
├── Link Item           plain link (class dwc-nest-nav-top-link)
└── Dropdown            mega menu (Mega menu setting on)
    └── Content         (class dwc-nest-nav-list)
        └── Content Inner
```

To add a menu item, duplicate one of the same kind and change its text and link.

***

## Dropdown settings

| Setting | Template value | What it does |
| --- | --- | --- |
| **Mega menu** | On for mega menus | Bricks' own setting. Turn it on to make the dropdown a mega menu. |
| **Toggle on** | Both | Bricks' own setting. Opens the dropdown on hover, click or both. |

***

## Dropdown attributes

Set these on the **Dropdown** element.

| Attribute | Values | What it does |
| --- | --- | --- |
| `data-submenu-reveal` | `slide`, `expand` | How this dropdown opens on mobile, offcanvas and sidebar. Overrides the value on Nav (Nestable). Leave empty to use that value. |
| `data-is-button` | No value | Styles the menu item as a button. Add it yourself when you need it. See [Styling](../styling.md#dropdown-item-as-a-button-or-icon). |
| `data-is-icon` | No value | Shows only the icon. The text stays readable for screen readers. Add it yourself when you need it. |

The `data-last-item-is-button` attribute on Nav (Nestable) doesn't work on dropdowns. Use `data-is-button` or `data-is-icon` instead.

***

## Mega menu Content attributes

Set these on the mega menu's **Content** element. See [Mega Menu Content](../mega-menu-content.md).

| Attribute | Values | What it does |
| --- | --- | --- |
| `data-content-width` | A number, CSS variable or selector | Width of this mega menu. Wins over `data-global-content-width`. |
| `data-content-align` | `left`, `right`, `center` | Lines this mega menu up with its menu item. Empty lines it up with the header. |
| `preview-alignment` | `true` | Builder only. Shows the `data-content-align` position in the builder. |
| `data-hide-instruction` | `true` | Builder only. Hides the instruction label. |
| `style` | Don't change | Mega Menu Pro uses it. |
