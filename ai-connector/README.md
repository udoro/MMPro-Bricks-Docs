---
icon: sparkles
---

# AI Connector

The AI Connector lets an AI agent work on your Mega Menu Pro header in Bricks. You describe what you want in plain English, and the agent changes your header template for you.

***

## What you need

* **Bricks 2.4 or later**, with Mega Menu Pro's header template imported.
* The **WordPress MCP Adapter** plugin, installed and active.
* The **MMPro AI Abilities** plugin, installed and active. Without it, the agent can read your header but can't save changes to it.
* [Node.js](https://nodejs.org) 18 or later.
* An **AI coding agent that can use MCP tools and run terminal commands**, such as [Claude Code](https://docs.claude.com/en/docs/claude-code/overview) or [Cursor](https://www.cursor.com).

A chat window with no tools won't work.

***

## Set it up

### Step 1: Turn on Bricks abilities

1. In WordPress, go to **Bricks > AI**.
2. Turn on **Enable Bricks abilities**.
3. Check the adapter status on the same screen. It should show the MCP Adapter as active.

### Step 2: Install MMPro AI Abilities

1. [Download MMPro AI Abilities](https://github.com/udoro/MMPro-Bricks-Docs/raw/main/ai-connector/mmpro-ai-abilities/releases/mmpro-ai-abilities-v0.3.0.zip).
2. In WordPress, go to **Plugins > Add New Plugin > Upload Plugin**.
3. Choose the zip file you downloaded and click **Install Now**.
4. Click **Activate**.

New versions show up in **Dashboard > Updates**, like any other plugin.

### Step 3: Create an application password

1. In WordPress, go to **Users > Profile**.
2. Under **Application Passwords**, enter a name, for example "AI agent", and click **Add**.
3. Copy the password. You'll only see it once.

Use an administrator account. Don't share this password, and revoke it when you stop using the agent.

### Step 4: Connect your agent to the site

Your agent connects straight to your site over HTTP.

**1. Make your sign-in token.** Run this in a terminal, with your WordPress username and the application password from step 3:

```bash
node -e "console.log(Buffer.from('your-username:xxxx xxxx xxxx xxxx xxxx xxxx').toString('base64'))"
```

Copy the line it prints. That's your token. Keep it private, like the password.

**2. Add the server.** In Claude Code, open a terminal in the project folder you'll work from and run:

```bash
claude mcp add --transport http my-bricks-site https://your-site.com/wp-json/mcp/mcp-adapter-default-server --header "Authorization: Basic YOUR_TOKEN"
```

For other agents, add this to their MCP settings:

```json
{
  "mcpServers": {
    "my-bricks-site": {
      "type": "http",
      "url": "https://your-site.com/wp-json/mcp/mcp-adapter-default-server",
      "headers": { "Authorization": "Basic YOUR_TOKEN" }
    }
  }
}
```

Put in your own site address and token.

Don't use the `mcp-wordpress-remote` bridge. With current versions of Claude Code it connects but loads no tools.

Start a new agent session after you add the server. Agents only load MCP servers when a session starts.

### Step 5: Give the agent the skills files

The skills files teach the agent how Mega Menu Pro works in Bricks.

1. Copy the `mmpro-bricks-skills` folder into your project folder.
2. Point your agent at `mmpro-bricks-skills/mega-menu-pro-bricks-skills.md`.

| File | What it is |
| --- | --- |
| `mega-menu-pro-bricks-skills.md` | The file you point your agent at. Short on purpose. |
| `mega-menu-pro-bricks-skills-build.md` | The full workflow. The agent reads it when it builds something new. |
| `mega-menu-pro-bricks-skills-reference.md` | Lookup tables. The agent searches it when it needs one setting. |

Keep the three files together in one folder.

***

## What you can ask for

**Change the look**

> *"Make the menu items dark navy, with orange on hover."*

> *"Give the dropdowns rounded corners and a soft shadow."*

**Change behavior**

> *"Open the mobile menu from the left, and make submenus expand in place."*

> *"Make the header float over the hero, and turn solid once I scroll."*

**Add to the menu**

> *"Add a Services mega menu with three columns: an icon, a title and a short description in each."*

> *"Add a Contact link at the end and make it a button."*

**For the best results**

* Attach a screenshot of a menu you like.
* Say how it should open: hover or click.
* Say what should happen on mobile.

The agent asks before it changes the structure of your header, and tells you how to undo a rebuild.

***

## Your context file

The agent keeps a file called `mmpro-bricks-user-context.md` next to the skills files. It notes your preferences and the layouts you've built, so the next session starts faster. The agent creates it on your first session and asks before it adds anything.

***

## Tips

* **Start each session with the skills file.** Agents don't remember earlier sessions.
* **Check the result on your site.** The agent checks your published pages, but it can't see inside your Bricks builder.
* **Undo a change** from the header template's revisions in Bricks. Every change the agent saves makes a new revision first.

***

## Troubleshooting

| Problem | What to do |
| --- | --- |
| The agent says it can't reach your site | Check step 4, then start a new session. |
| The server shows as connected, but the agent has no Bricks tools | You're using the `mcp-wordpress-remote` bridge. Set the server up over HTTP, as in step 4. |
| The agent says it can't save changes to your header | Install and activate MMPro AI Abilities (step 2), then start a new session. |
| The agent says Mega Menu Pro's code isn't running | Go to **Bricks > Settings > Custom code** and check **Code execution** is on. |
| The agent says an ability is turned off | Go to **Bricks > AI** and turn that ability on. |
| The agent can't find your header | Import the Mega Menu Pro header template first. See [Installation](../getting-started.md). |
| Changes show in the builder but not on your site | Clear your site's cache. |
