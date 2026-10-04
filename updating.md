---
icon: cloud-arrow-up
---

# Updating

Most updates are small CSS fixes. You copy the new CSS and paste it into a code block that's already in your header.

If an update needs more, you'll be told which code blocks to replace. Usually it's **MEGA MENU Codes**.

Don't replace **MENU Styles / Options** unless the update tells you to. It holds your settings, and replacing it resets them.

***

## Replace a code block

1. Open your header template in the builder.
2. Select the code block named in the update, for example **MEGA MENU Codes**.
3. Replace all of its CSS, and its JS if the update includes JS.
4. Save.
5. If Bricks asks you to sign the code, sign it. See [Installation](getting-started.md#turn-on-code-execution).

If you've moved a block into a code manager, update it there instead.

***

## Several headers on one site

If your site has more than one header, move the shared code out of the headers. Then you update it in one place. See [Code Blocks](elements/code-blocks.md#several-headers-on-one-site).

***

## Older versions

**Bricks 2.0.2** renamed its sticky header class from `.sticky` to `.brx-sticky`. If you use a sticky header with Mega Menu Pro **1.4.1 or below**, add this CSS to the bottom of the **MEGA MENU Codes** CSS:

```css
body:has(.brx-has-megamenu.open) .brx-sticky .dwc-nest-header::after {
  background-color: var(--adaptive-height-bg) !important;
}
html:not(.dwc-mobile) .bricks-is-frontend #brx-header:has([data-overlay-header="true"]):not(.brx-sticky) {
  position: absolute;
  inset-block-start: 0;
  inset-inline: 0;
}
.dwc-mobile .bricks-is-frontend #brx-header:has([data-overlay-header-mobile="true"]):not(.brx-sticky) {
  position: absolute;
  inset-block-start: 0;
  inset-inline: 0;
}
```

**Updating from 1.2 to 1.4 or later:** if the menu slides off screen when you resize from desktop to mobile, your old Nav wrapper (`.dwc-nav-wrapper`) is missing newer CSS. Copy the custom CSS from the Nav wrapper in the new template into yours.
