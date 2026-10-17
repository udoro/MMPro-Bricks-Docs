---
icon: hand-pointer
---

# Open in Menu

A link or button anywhere on your site can open a menu in your header. For example, a "See all features" button that opens your Features mega menu, or a "Shop by category" link that opens your shop menu on the right tab.

* **On desktop**, the dropdown opens. If the header is scrolled out of view, the page scrolls up to it first.
* **On mobile and in offcanvas mode**, the mobile menu opens and the submenu slides in.

It's built into the **MEGA MENU Codes** code block, in Full and Lite, from version 1.4.8. There's nothing to turn on. Try it on the front end of your site.

***

## Open a menu with a link

In the link's URL field, type `#menu=` and then the menu item's name.

| URL | What it opens |
| --- | --- |
| `#menu=features` | The **Features** dropdown |
| `#menu=use-cases` | The **Use cases** dropdown |
| `#menu=features/mobile-menu` | The **Features** dropdown, on its **Mobile Menu** tab |
| `/pricing/#menu=docs` | The Pricing page. Once it loads, the **Docs** dropdown opens. |

This works in any URL field: a Button, a Text Link, a Div or Block set to a link, and the menus under **Appearance > Menus** in WordPress. It works inside a mega menu too, for example a promo in Features that opens Docs.

### The menu item's name

The name is the text of the menu item, in lowercase, with a dash between words.

| Menu item text | Name |
| --- | --- |
| Features | `features` |
| Use cases | `use-cases` |
| Shop & Gifts | `shop-gifts` |

Anything that isn't a letter or a number becomes one dash.

***

## Open a menu from any element

Add the `data-open-in-menu` attribute to the element. Set its value to the menu item's name, for example `features`. See [Change an attribute](README.md#change-an-attribute).

Use a link when you can. Keyboard users can reach a link, but not a plain Div, or a Bricks Button that has no link.

***

## Open a tab or a submenu

Add the next name after a slash. Each name is one level deeper.

| Name | What it opens |
| --- | --- |
| `features/mobile-menu` | The **Mobile Menu** tab inside the **Features** dropdown ([Tabbed Navigation](add-ons/tabbed-navigation.md)) |
| `resources/guides` | A dropdown called **Guides**, inside the **Resources** dropdown |

For a tab, the name is the tab's text.

***

## Give a menu item a fixed name

The name comes from the menu item's text, so a link stops working if you rename the item. To avoid that, give the item a fixed name:

1. Select the **Dropdown** element. For a tab, select its **tabbed nav li** element.
2. Add the `data-menu-id` attribute with a name, such as `features`.

Links can then use that name, whatever the item's text says. Use it too when two menu items have the same text.

A Menu ID works on its own: you don't need the levels around it. If the **Mobile Menu** tab has the Menu ID `phones`, the link `#menu=phones` opens the **Features** dropdown on that tab. It keeps working when you rename the tab or the dropdown.

***

## Open a menu with JavaScript

```js
window.dwcMegaMenu.openInMenu('features/mobile-menu');
```

It returns a promise. The promise gives `true` when the menu opened, or `false` when no menu item has that name.

***

## Troubleshooting

* **Nothing opens.** Open your browser's console. If it says `Open in Menu: nothing in the menu matches`, check the name: lowercase, a dash between words, and a slash between levels.
* **The address bar shows `#menu=` but nothing opens.** Your header is older than 1.4.8. See [Updating](updating.md).
