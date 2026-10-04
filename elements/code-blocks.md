---
icon: code
---

# Code Blocks

Mega Menu Pro's CSS and JavaScript live in code blocks inside the header template. Bricks must have code execution on to run them. See [Installation](../getting-started.md#turn-on-code-execution).

Some steps below mention a **code manager**. That's a plugin that loads your CSS and JavaScript across the whole site, such as WPCodeBox. You don't need one: the code blocks work fine where they are.

***

## The code blocks

| Code block | What's in it | Edit it? | Move it to a code manager? |
| --- | --- | --- | --- |
| **MENU Styles / Options** | Your CSS variables and JS options | Yes | Yes. The builder preview may stop updating as you edit, depending on the code manager. |
| **MEDIA QUERY** (Lite: **MENU Media Query**) | Mobile, offcanvas and sidebar styles | No | Only if you don't use offcanvas or sidebar mode. Those features break if it moves. |
| **MEGA MENU Codes** | The main CSS and JavaScript | No | Yes |
| **SIDEBAR Codes** | **Full only.** Sidebar CSS and JavaScript | No | Yes. You can delete it if you don't use sidebar mode. |

**SIDEBAR Codes** is the only block you can delete. Deleting any other block breaks the menu, unless its code is in your code manager.

Keep the `data-stylesheet` attribute on the **MEDIA QUERY** block. Offcanvas and sidebar modes need it.

***

## Several headers on one site

If you have more than one header, you can move the shared code out of the headers. Then you edit and update it in one place.

* **Same styles on every header:** move the CSS and JS of one **MENU Styles / Options** block into your site-wide code manager. Then delete that block from every header.
* **Different styles per header:** keep **MENU Styles / Options** in each header.
* **MEGA MENU Codes:** move it to your code manager either way.
* **MEDIA QUERY:** move it too, unless a header uses offcanvas or sidebar mode. Those headers must keep it in the builder.
* **SIDEBAR Codes:** only keep it if you use sidebar mode.

If no header uses offcanvas or sidebar mode, you can move every block out and leave your header templates clean.
