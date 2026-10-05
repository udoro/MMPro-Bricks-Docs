---
icon: cloud-arrow-up
---

# Updating

Most updates are small CSS fixes. The update tells you which code block the CSS goes in. Paste it at the bottom of that block's CSS tab, unless the update says otherwise.

Bigger updates replace a whole code block. The update tells you which one. Usually it's **MEGA MENU Codes**.

Don't replace **MENU Styles / Options** unless the update tells you to. It holds your settings, and replacing it resets them.

***

## Replace a code block

1. Open your header template in the builder.
2. Select the code block the update names, for example **MEGA MENU Codes**.
3. Click into its CSS tab, select everything (Ctrl+A, or Cmd+A on a Mac), and paste the new CSS over it.
4. If the update also has JavaScript, do the same in the JS tab.
5. Save.
6. If Bricks asks you to sign the code, sign it. See [Installation](getting-started.md#turn-on-code-execution).

If you've moved the block into a code manager, replace the code there instead. See [Code Blocks](elements/code-blocks.md).

***

## Several headers on one site

If your site has more than one header, move the shared code out of the headers. Then you update it in one place. See [Code Blocks](elements/code-blocks.md#several-headers-on-one-site).

***

## Older versions

**Bricks 2.0.2** renamed its sticky header class from `.sticky` to `.brx-sticky`. If you use a sticky header with Mega Menu Pro **1.4.2 or below**, add this CSS to the bottom of the **MEGA MENU Codes** CSS tab:

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

**Updating from 1.2 to 1.4 or later:** if the menu slides off screen when you resize from desktop to mobile, your **nav wrapper** element is missing newer CSS. To copy it over:

1. Import the new header template. Don't set conditions on it.
2. Open it in the builder and select its **nav wrapper** element.
3. Open **Style > CSS** and copy everything in **Custom CSS**.
4. Open your own header template, select its **nav wrapper**, and paste into the same field.
5. Save. You can then delete the template you imported.
