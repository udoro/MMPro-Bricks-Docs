---
icon: desktop-arrow-down
---

# Installation & Getting Started

## Installation

### Import the header template

1. Download the JSON file from your Gumroad dashboard. Pick **Full** or **Lite** (see [Full or Lite](README.md#full-or-lite)).
2. In WordPress, go to **Bricks > Templates** and click **Import**.
3. Select the JSON file. If you see **Import images**, tick it if you want the template's images too.
4. Click **Import**.
5. Open the new template with **Edit with Bricks**.
6. In the builder, open **Settings > Template Settings > Conditions** and add a condition, for example **Entire website**. This makes the header show on your pages.
7. Click **Save**.

### Turn on code execution

Mega Menu Pro runs from code blocks inside the template. Bricks only runs them when code execution is on.

1. Go to **Bricks > Settings > Custom code**.
2. Turn on **Code execution**.
3. If Bricks shows a code signature warning on the imported code blocks, sign them. You can sign all of them at once with **Regenerate code signatures** on the same settings page.

***

## What's inside the template

```
Header Pro | v1.4.7                (Header Pro element: header attributes)
└── Container
    ├── Logowrap (link)            (your logo)
    └── Menu wrap
        └── Nav (Nestable)         (menu attributes)
            ├── Toggle (Open: Mobile)
            └── nav wrapper
                ├── mobile top bar (mobile logo + close toggle)
                └── Nav items
                    ├── Dropdown           (multilevel dropdown)
                    ├── Link Item          (plain link)
                    └── Dropdown           (mega menu)
                        └── Content
                            └── Content Inner   (your mega menu layout)
MENU Styles / Options              (code block: your CSS variables and JS options)
MEDIA QUERY                        (code block: don't edit)
MEGA MENU Codes                    (code block: don't edit)
SIDEBAR Codes                      (code block: Full only, don't edit)
```

* The **Header Pro** element holds the header settings, such as the overlay header. See [Header Pro](elements/header-pro.md).
* The **Nav (Nestable)** element holds the menu settings, such as offcanvas mode and mobile menu direction. See [Nav (Nestable)](elements/nav-nestable.md).
* Each **Dropdown** element is a top-level menu item with a submenu or a mega menu. See [Dropdown](elements/dropdown.md).
* The code blocks hold the CSS and JavaScript. You only edit **MENU Styles / Options**. See [Code Blocks](elements/code-blocks.md).

***

## Starter templates

Starter templates are ready-made mega menu layouts.

### Add them to Bricks

1. In your Gumroad dashboard, click **Starter Template** in the left panel.
2. Copy the URL and the password.
3. In WordPress, go to **Bricks > Settings > Templates > Remote Templates**.
4. Enter a name, for example "Mega Menu Pro Templates".
5. Paste the URL and the password into their fields.
6. Save the settings.

New templates and fixes show up in the remote templates list on their own. A template you've already added to your site doesn't update. To get the new version, add it again.

### Use a starter template

1. In the builder, select the mega menu's **Content** element in the Structure panel.
2. Click the **Templates** icon in the top bar.
3. In the **Source** dropdown, pick the remote templates you added.
4. Insert the template you want.
5. Check it sits inside **Content**. If it doesn't, drag it there in the Structure panel.
6. Delete the old **Content Inner** element that was already in **Content**.

A starter template replaces the Content Inner element. Don't put it inside Content Inner, because every starter template is already a Content Inner element.

To build mega menu content yourself, see [Mega Menu Content](mega-menu-content.md#build-your-own-mega-menu-content).
