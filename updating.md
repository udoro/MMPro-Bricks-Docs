---
icon: cloud-arrow-up
---

# Updating

The **MMPro Updater** moves your header to a new version of Mega Menu Pro and keeps your settings. You don't need to redo your styles or options after an update.

**Open the updater:** [bricks.designwithcracka.com/mmpro-updater](https://bricks.designwithcracka.com/mmpro-updater/)

**Watch it in action:** [Quicker updates for Bricks Mega Menu Pro!](https://youtu.be/XaGLRo6DYBA)

{% embed url="https://youtu.be/XaGLRo6DYBA" %}

***

## What the updater keeps

From your current header, the updater keeps:

* Your CSS variables and menu options from the **MENU Styles / Options** code block
* Your attributes
* Your menu items and mega menu content
* Your logo, on desktop and mobile
* Your own classes, such as the ones from extra starter templates

It replaces the **MEGA MENU Codes** code block with the new version. If your header doesn't have the **SIDEBAR Codes** code block, it leaves that block out.

Elements you added to the header yourself, such as an extra header row, aren't carried over. You move them across by hand in step 5.

***

## Step 1: Copy your current header

1. Open your header template in the builder.
2. In the Structure panel, click **Header Pro**.
3. Hold **Shift** and click the last code block. This selects the header and all its code blocks.
4. Press **Ctrl+C** (**Cmd+C** on a Mac).
5. In the updater, under **Your Current Version**, paste into the **Old JSON** box.

***

## Step 2: Load the new version

1. Download the new template file from your Gumroad dashboard. Pick the same kind you use now: **Full** or **Lite**.
2. In the updater, under **Updated Release**, drop the file on **New JSON**, or click it to choose the file.
3. Click **Review Changes**.

If the updater says your files may be in the wrong order, check that your current header is in **Old JSON** and the download is in **New JSON**.

***

## Step 3: Review the changes

The updater lists every CSS value that's different in the new version. For each one, choose:

* **Keep yours** to keep your value. It's selected by default.
* **Use new** to take the value from the new version.

Some changes are whole rules:

* A rule that's new in the update is included. Untick it to leave it out.
* For a rule the update removed, tick it to keep your copy, or untick it to accept the removal.

Also check these two warnings, if they show:

* **Custom elements detected:** elements you added to the header yourself. They won't be in the result. Note them down, because you'll move them across in step 5. The updater shows each one's element ID.
* **Global classes only in your file:** classes you have that the new version doesn't, usually your own. They're kept. Untick any you don't want.

Everything under **Auto-applied actions** happens on its own. You don't need to do anything there.

When you're done, click **Apply & Generate**.

***

## Step 4: Copy the result

The **Update Complete** screen lists what the updater did. Anything marked **failed** wasn't carried over. For example, if the **MENU Styles / Options** code block wasn't in what you copied, your variables and options aren't in the result. Click **Start Over** and copy your header again, with all its code blocks.

When everything looks right, click **Copy JSON**.

***

## Step 5: Paste it into your header

Do all of these, in order:

1. In the builder, click an empty spot on the canvas below your header, so nothing is selected.
2. Press **Ctrl+V** (**Cmd+V** on a Mac). The new header and its code blocks appear below your old ones.
3. Move the elements you noted in step 3 from your old header into the new one.
4. Delete your old header and your old code blocks.
5. Save, then check your site.

***

## Notes

* The updater reads your settings from the code blocks in your header. If you moved a code block into a code manager, update that code by hand: copy it from the new version.
* If something goes wrong, restore your header template from its revisions in Bricks.
* After a big version jump, you may need a few small fixes by hand.
