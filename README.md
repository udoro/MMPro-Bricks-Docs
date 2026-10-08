---
icon: pen-to-square
---

# Documentation

**Mega Menu Pro + Header Builder for Bricks** is a header and navigation template for Bricks Builder. It gives you a responsive menu, mega menus, an overlay header, offcanvas and sidebar navigation, a centered logo, and a mobile menu with slide or expand submenus.

This documentation covers version **1.4.7**.

***

## What's included

| Part | What it is |
| --- | --- |
| [Header template](getting-started.md) | The Bricks header template you import. Comes in two versions: **Full** and **Lite**. |
| [Starter templates](getting-started.md#starter-templates) | Ready-made mega menu layouts you drop into a dropdown. |
| [Adaptive Header Styling](add-ons/adaptive-header-styling.md) | Add-on. The header changes its colors to match the section under it. |
| [Tabbed Navigation](add-ons/tabbed-navigation.md) | Add-on. A tabbed, multi-level panel for large menus, such as WooCommerce categories. |
| [AI Connector](ai-connector/README.md) | Lets an AI agent build and edit your header for you. |

***

## Full or Lite

Both versions share the same menu, mega menus, mobile menu and styling. Lite leaves out some features to keep the code smaller.

| Feature | Full | Lite |
| --- | --- | --- |
| Mega menus, dropdowns, mobile menu | Yes | Yes |
| Overlay header, sticky styles, centered logo | Yes | Yes |
| [Breakout, breakin and breakinto](moving-elements.md) (moving elements in and out of the mobile menu) | Yes | Yes |
| [Offcanvas navigation](mobile-offcanvas-sidebar.md#offcanvas-navigation) on desktop | Yes | No |
| [Sidebar navigation](mobile-offcanvas-sidebar.md#sidebar-navigation) | Yes | No |
| [Adaptive height and Stripe style](menu-options.md#menu-options) (animated mega menu panel) | Yes | No |
| [Swipe to close](mobile-offcanvas-sidebar.md#swipe-to-close) and its tooltip | Yes | No |
| [Auto menu expansion](menu-options.md#menu-options) (opens the current page's submenu) | Yes | No |

***

## How you customize it

You change Mega Menu Pro in three places:

1. **Attributes** on the elements. These turn features on and off, such as the overlay header or offcanvas mode. See [Header Pro](elements/header-pro.md), [Nav (Nestable)](elements/nav-nestable.md) and [Dropdown](elements/dropdown.md).
2. **CSS variables** in the CSS tab of the **MENU Styles / Options** code block. These set colors, sizes and spacing. See [Styling](styling.md).
3. **JavaScript options** in the JS tab of the same code block. These control behavior, such as the breakpoint or Stripe style. See [Menu Options](menu-options.md).

You don't need to touch the other code blocks. See [Code Blocks](elements/code-blocks.md).

### Change an attribute

1. In the builder, select the element in the Structure panel, for example **Nav (Nestable)**.
2. Open the **Style** tab, then **Attributes**.
3. Find the attribute by its **Name** and change its **Value**.

Most attributes are already on the template's elements. To turn a setting off, clear its **Value** and leave the attribute where it is, so you can turn it on again later.

Some pages ask you to add an attribute that isn't there yet. Add a new attribute in **Attributes**, type its name in **Name**, and type the value in **Value**. If the page says it needs no value, leave **Value** empty.
