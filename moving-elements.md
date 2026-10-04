---
icon: arrows-up-down-left-right
---

# Moving Elements

Three attributes move elements between the header and the mobile menu as the screen gets smaller.

| Attribute | Moves | Use it for |
| --- | --- | --- |
| `data-breakout-link` | A menu link out of the mobile menu, into the header | A CTA button that should stay visible next to the toggle |
| `data-breakin` | Any element from the header into the mobile menu | Search, social icons or a phone number that should go inside the mobile menu |
| `data-breakinto` | Any element into any container you choose | Anything else, such as moving an element into a top bar |

Add the attribute to the element you want to move. See [Change an attribute](README.md#change-an-attribute).

***

## data-breakout-link

Moves a menu link from the mobile menu into the header. It only works on a plain link, not on a **Dropdown** element.

| Value | Result |
| --- | --- |
| Empty | Below the breakpoint, the link moves into the header. |
| A number, such as `560` | The link moves into the header below the breakpoint, then goes back into the mobile menu below 560px. |

Above the breakpoint, the link stays in the menu.

The template's last menu item uses `data-breakout-link="520"`. If you don't want that link in the mobile header, remove the attribute.

***

## data-breakin

Moves an element from the header into the mobile menu.

| Value | Result |
| --- | --- |
| Empty | The element moves into the mobile menu below the breakpoint. |
| A number, such as `767` | The element moves into the mobile menu at 767px and below. |
| `end` | Pushes the breakin container to the bottom of the mobile menu. |

The element goes into a container with the class `breakin-container`. Where that container sits depends on the `breakinToNavList` option:

* `1` (default): inside the list of menu items, shown last.
* `0`: at the end of the nav wrapper.

See [Menu Options](menu-options.md).

When the screen gets wider again, the element goes back to the header.

### Style a moved element

Target it through the container. For example, to change a text color once it's in the mobile menu:

```css
.breakin-container .my-text {
  color: gray;
}
```

***

## data-breakinto

Moves an element into any container, below the breakpoint or a width you choose.

Set the value to the container's selector, such as its class or ID. To use your own width, add a bar (`|`) and the width after it.

```
data-breakinto=".my-div-class"          moves into .my-div-class below the breakpoint
data-breakinto=".my-div-class | 767"    moves into .my-div-class at 767px and below
```

The target can be any class or ID: the header, a top bar, the menu wrap or the mobile menu.

***

## Older versions

**Below 1.4.1**, `data-breakout-link` and `data-breakin` took no value. You set the width with a second attribute, `data-breakpoint`, for example `data-breakpoint="560"`. `data-breakinto` didn't exist.

**1.4.2 and below**, `breakinToNavList` defaulted to `0`.
