---
icon: circle-question
---

# Troubleshooting

## The menu closes when I click a link. Is that right?

Yes. The menu closes on click so the site feels quick while the next page loads.

To change this, use these options in the JS tab of **MENU Styles / Options**:

* `closeNavOnClick`: set to `0` to stop it.
* `closeOnHashClickOnly`: set to `1` so only links to a spot on the same page close it.

See [Menu Options](menu-options.md).

***

## My last menu item shows as a button in the mobile header

The item has the `data-breakout-link` attribute, which moves it into the header on mobile. If you don't want that, remove the attribute from the link. See [Moving Elements](moving-elements.md#data-breakout-link).

***

## The page jumps when the menu opens

When the offcanvas or mobile menu opens, the page stops scrolling and the scrollbar disappears. The page shifts by the width of the scrollbar.

To stop the shift, add this CSS:

```css
body.no-scroll {
  overflow: visible !important;
}
```

The page then scrolls behind the open menu.

To hide the dark overlay behind the open menu, set the `data-hide-overlay` attribute on **Nav (Nestable)** to `true`.

***

## I want to use the Bricks Logo element

Don't put the Logo element inside the **Logowrap** element. Instead:

1. Add the class `dwc-nest-menu__logo` to the Logo element.
2. Delete the Logowrap element.

***

## Can the mega menu appear from behind the header?

The closest effect is Adaptive height. Turn on the `adaptiveHeight` option. See [Menu Options](menu-options.md).

***

## The adaptive height background is the same color as the header

Wrap the header's container (the direct child of **Header Pro**) in a **Block** element. The block takes the header's background, and the adaptive height background shows behind it. Header Pro's left and right padding moves to the block on its own.

***

## data-is-button or data-is-icon does nothing

These attributes need version 1.4.1 or later.

***

## Multilevel dropdown links wrap onto two lines

Set `--multilevel-dropdown-width` to `max-content` in the **MENU Styles / Options** CSS tab. The dropdown then fits its longest link.

***

## How do I turn off the dropdowns and only use a slide-in menu?

Use offcanvas mode. See [Offcanvas navigation](mobile-offcanvas-sidebar.md#offcanvas-navigation).

***

## Stripe style or Adaptive height looks wrong

Check these in order:

1. **Zero values have a unit.** Write `0px`, not `0`. A bare `0` breaks the size and position.
2. **Your code manager keeps the unit.** WPCodeBox strips the unit from `0px` on the frontend. Use `0.1px` instead.
3. **Mega menus sit at the bottom of the header.** If not, set the `data-global-content-vertical` attribute on **Nav (Nestable)** to your header's selector, such as `#brx-header`.

***

## Bricks warns about orphaned elements

Orphaned elements are elements left behind when their parent was deleted. It can happen after a Bricks bug or after importing a template with broken data.

Since Bricks 2.0, the builder checks for them when it loads and offers to **Clean up**.

1. Back up your site first.
2. Click **Clean up**.
3. Check the page. If something's wrong, click **Undo**.
