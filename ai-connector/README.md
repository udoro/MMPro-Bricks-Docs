---
icon: sparkles
---

# AI Connector

The AI Connector lets an AI agent work on your Mega Menu Pro header in Bricks. You describe what you want in plain English, and the agent changes your header template for you.

***

## What you need

* **Bricks 2.4 or later**, with Mega Menu Pro's header template imported.
* An **AI agent that can use MCP tools**, such as [Claude Code](https://docs.claude.com/en/docs/claude-code/overview), [Codex](https://openai.com/codex/) or [Cursor](https://www.cursor.com).

A chat window with no tools won't work.

***

## Set it up

### Step 1: Set up the Bricks MCP server

Follow this video: [How to Set Up the Bricks MCP Server (Bricks 2.4+)](https://youtu.be/JVsinkC7psM).

Come back here once your agent is connected to your site.

### Step 2: Install MMPro AI Abilities

1. [Download MMPro AI Abilities](https://github.com/udoro/MMPro-Bricks-Docs/raw/main/ai-connector/mmpro-ai-abilities/releases/mmpro-ai-abilities-v0.3.1.zip).
2. In WordPress, go to **Plugins > Add New Plugin > Upload Plugin**.
3. Choose the zip file you downloaded and click **Install Now**.
4. Click **Activate**.

New versions show up in **Dashboard > Updates**, like any other plugin.

### Step 3: Add the skills files

The skills files teach your agent how Mega Menu Pro works in Bricks.

1. Make a folder on your computer for this work, for example `Mega Menu Pro`. Open it in your agent and start a session there.
2. Paste this into the chat and send it:

```
Use curl to download these three files into a folder called mmpro-bricks-skills in this project. Then read mmpro-bricks-skills/mega-menu-pro-bricks-skills.md and follow it.
https://raw.githubusercontent.com/udoro/MMPro-Bricks-Docs/main/ai-connector/mmpro-bricks-skills/mega-menu-pro-bricks-skills.md
https://raw.githubusercontent.com/udoro/MMPro-Bricks-Docs/main/ai-connector/mmpro-bricks-skills/mega-menu-pro-bricks-skills-build.md
https://raw.githubusercontent.com/udoro/MMPro-Bricks-Docs/main/ai-connector/mmpro-bricks-skills/mega-menu-pro-bricks-skills-reference.md
```

To update the skills later, paste the same message again.

**Prefer a manual download?** [Download this repository](https://github.com/udoro/MMPro-Bricks-Docs/archive/refs/heads/main.zip), copy the `ai-connector/mmpro-bricks-skills` folder into your project folder, and tell your agent to read `mmpro-bricks-skills/mega-menu-pro-bricks-skills.md`.

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

The agent keeps a file called `mmpro-bricks-user-context.md` next to the skills files. It notes your preferences and the layouts you've built, so the next session starts faster. The agent creates it on your first session and asks before it adds anything. Updating the skills files keeps it.

***

## Tips

* **Start each session by telling your agent to read `mmpro-bricks-skills/mega-menu-pro-bricks-skills.md`.** Agents don't remember earlier sessions.
* **Check the result on your site.** The agent checks your published pages, but it can't see inside your Bricks builder.
* **Undo a change** from the header template's revisions in Bricks. Every change the agent saves makes a new revision first.

***

## Troubleshooting

| Problem | What to do |
| --- | --- |
| The agent says it can't reach your site | Check your connection with the video in step 1, then start a new session. |
| The server shows as connected, but the agent has no Bricks tools | See [Connected, but no Bricks tools](#connected-but-no-bricks-tools) below. |
| The agent says it can't save changes to your header | Install and activate MMPro AI Abilities (step 2), then start a new session. |
| The agent says Mega Menu Pro's code isn't running | Go to **Bricks > Settings > Custom code** and check **Code execution** is on. |
| The agent says an ability is turned off | Go to **Bricks > AI** and turn that ability on. |
| The agent can't find your header | Import the Mega Menu Pro header template first. See [Installation](../getting-started.md). |
| Changes show in the builder but not on your site | Clear your site's cache. |

### Connected, but no Bricks tools

If your agent's MCP settings mention `mcp-wordpress-remote`, connect over HTTP instead. Do all three steps, in order.

**1. Make your sign-in token.** Copy this command:

```bash
node -e "console.log(Buffer.from('your-username:your-application-password').toString('base64'))"
```

Before you run it in a terminal, replace both placeholders. Keep the colon between them.

* `your-username`: your WordPress username. It's the `WP_API_USERNAME` value in your current MCP settings.
* `your-application-password`: your application password, spaces included. It looks like `abcd efgh ijkl mnop qrst uvwx`. It's the `WP_API_PASSWORD` value in your current MCP settings.

Copy the line it prints. That's your token. Keep it private, like the password.

**2. Swap the server.** First remove the old server:

* In Claude Code, run `claude mcp list` to see the old server's name. Then run `claude mcp remove` followed by that name, for example `claude mcp remove my-old-site`.
* In other agents, delete the old server's entry from their MCP settings.

Then add the new server. In Claude Code, open a terminal in your project folder and run:

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

Put in your own site address and token. `my-bricks-site` is only a name for the server, so you can use any name you like, such as your site's name.

**3. Start a new agent session.** Agents only load MCP servers when a session starts.
