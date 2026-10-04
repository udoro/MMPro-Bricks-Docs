---
icon: mobile
---

# Mobile, Offcanvas & Sidebar

Below the breakpoint, the menu becomes a mobile menu that opens from the menu toggle. To change the breakpoint, see [Menu Options](menu-options.md#breakpoint).

Mega Menu Pro runs the mobile menu itself. On the Nav (Nestable) element, leave Bricks' own **Mobile menu > Show at breakpoint** setting on **Never**.

Most mobile settings are attributes on the **Nav (Nestable)** element. See [Nav (Nestable)](elements/nav-nestable.md) for the full list, and [Change an attribute](README.md#change-an-attribute) for how to set one.

***

## Mobile menu

### Where it opens from

Set the `data-slide-in-direction` attribute on **Nav (Nestable)**.

| Value | Opens from |
| --- | --- |
| Empty | Right |
| `left` | Left |
| `top` | Top right |
| `bottom` | Bottom right |
| `left top` | Top left |
| `left bottom` | Bottom left |

To open the menu below the header instead of over it, set `data-below-header` to `true`.

### How submenus open

Set the `data-submenu-reveal` attribute on **Nav (Nestable)** to `slide` or `expand`.

* `slide`: the submenu slides in over the menu, with a back button.
* `expand`: the submenu opens in place, like an accordion.

You can also set `data-submenu-reveal` on a single **Dropdown** element. That dropdown then uses its own value. For the best result, set the Nav to `slide` and the dropdowns you want to open in place to `expand`.

### Back button

When a submenu slides in, a back bar shows at the top.

* `data-back-text` sets its text. Use `auto` to show the name of the menu item, such as "Back to Products". Any other text replaces it, for example `Back`.
* `data-hide-close-bar` set to `true` hides the bar.
* `data-mobile-top-transparent` set to `true` places the bar over the header, with your logo showing until a submenu opens. The template has it on. Leave it on.

### Menu toggle

Style the toggle with the toggle variables. See [Styling](styling.md#menu-toggle-hamburger).

You can put the toggle anywhere in the header. If you add a new **Toggle** element outside the Nav (Nestable):

1. Select the toggle and open its Content panel.
2. Under **TOGGLE**, find the **CSS selector** field.
3. Enter `.brxe-nav-nested`.

Enter it in that field. Don't add it to the toggle as a class.

To keep the toggle on screen while a submenu is open, set `data-show-toggle-always` on **Nav (Nestable)** to `true`. This works when `data-submenu-reveal` is `slide`.

### Mobile logo

The mobile logo shows in the top bar of the mobile menu. It's hidden unless you set `data-show-mobile-logo` on **Nav (Nestable)** to `true`.

The logo sits in the **mobile Logowrap** element.

* Leave it empty to use your desktop logo.
* Add an image to it to use a different logo.
* Don't delete it, even when it's empty.

### Swipe to close

**Full only.** Visitors can swipe to go back or close the menu. A tooltip shows them how.

* To hide the tooltip, set the `toolTip` option to `false`. Swiping still works.
* To turn swiping off, set the `swipeToClose` option to `false`.
* To change the tooltip text, set the `data-tooltip-back-text` attribute on **Nav (Nestable)**.

See [Menu Options](menu-options.md).

### Tablet style

Set the `data-mobile-special-style` attribute on the **Header Pro** element to `slide-split`. This gives the mobile menu a preset reveal on tablets. Leave it empty to turn it off.

### Make the mobile menu only as tall as its content

By default, the mobile menu fills the screen height. To make it fit its content:

1. On **Header Pro**, set `data-overlay-header` and `data-overlay-header-mobile` to `true`.
2. In the **MENU Styles / Options** CSS tab, set `--overlay-header-inset` to `0px`. Keep the `px`.
3. Set `--overlay-header-width` and `--overlay-header-radius` to suit your design. For a full-width header, set `--overlay-header-width` to `100%`.
4. On **Nav (Nestable)**, set `data-slide-in-direction` to `top`.
5. Optional: on **Nav (Nestable)**, set `data-submenu-reveal` to `expand`.

If you don't want rounded corners on desktop, set `--overlay-header-radius` to `0px`. Then set the mobile radius in the `html.dwc-mobile` rule at the top of the CSS tab:

```css
html.dwc-mobile {
  --overlay-header-radius: 1rem;
}
```

***

## Offcanvas navigation

**Full only.** Offcanvas mode shows the menu toggle on desktop too. The menu opens from the side, like the mobile menu.

To turn it on, set the `data-offcanvas` attribute on **Nav (Nestable)** to `true`. Leave it empty to turn it off.

Offcanvas uses the same direction and submenu settings as the mobile menu.

{% hint style="info" %}
Offcanvas isn't the mobile menu. It's for showing a side menu on desktop. Don't raise the breakpoint to get this look.
{% endhint %}

***

## Sidebar navigation

**Full only.** Sidebar mode places the header on the left or right side of the page.

### Turn it on

1. In the builder, click the **Settings** icon in the top bar.
2. Go to **Template Settings > Header**.
3. Set **Header Location** to left or right.

To turn it off, click the yellow dot next to **Header Location**.

### Show the menu while you edit

In sidebar mode, the menu is hidden in the builder until you add your header template's ID.

1. Find the ID: in WordPress, go to **Bricks > Templates**. The ID is in the **Shortcode** column.
2. Open the **MENU Styles / Options** CSS tab and scroll to the bottom.
3. Change every `postid-23338` to `postid-` plus your ID, for example `postid-512`.

### Sidebar options

On **Nav (Nestable)**:

* `data-overlay-sidebar` set to `true` floats the sidebar over the page, so full-width sections run behind it. Style it with the `--overlay-sidebar-*` variables.
* `data-sidebar-back-text-on-logo` set to `true` lines up the back bar with the top of the header.

***

## Edit the offcanvas or sidebar menu in the builder

To see and edit the menu in offcanvas or sidebar mode, keep it open in the builder:

1. Select the **Nav (Nestable)** element.
2. In the Content tab, open **Mobile Menu**.
3. Turn on **Keep open while styling**.

To edit a dropdown, select its parent **Dropdown** element in the Structure panel. It opens on the canvas.
