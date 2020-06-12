# ItemBrag

A pocketmine plugin that allows players to brag about their items.

# How to use

1. Download the phar from poggit.

2. Upload to your servers 'plugin' folder.

3. Restart your server.

# Commands

/brag:

Hold the item you wish to brag to other players, run /brag and the items name will appear in chat along with its count.
Any enchants will appear aslong as you have the permissions which can be found in the permissions section of the plugin.

Currently piggy ce support and the levels of enchants DO NOT appear in chat, however these are coming very soon.

# Config

Set this to true if you wish to disable /brag without uninstalling the plugin

brag-disabled: false

Message sent to player if brag-feature-disabled is set to true

brag-feature-disabled-message: "§cThis command is currently disabled!"

Message sent to player if they run /brag and their hand is empty

message-sent-to-player-when-hand-is-empty: "§cYou must hold an item while running this command!"

Message sent to player if they lack the permissions to brag

no-perms-message: "§cYou do not have permission to use this command!"

Message sent to player if they lack the permission to brag about items with enchants on

no-perms-message-enchants: "§cYou do not have permission to brag about items with enchants on!"

# Permissions

permissions:

  brag.allow:
  
    description: Allows players to brag using /brag.
    default: op

  brag.allow.enchants:
  
    description: Allows players to brag about items with enchants on.
    default: op
    
# Plans

- Adding piggy ce support soon!

- Enchant levels are being added within the next few days (I know this will be heavily requested).

[![](https://poggit.pmmp.io/shield.state/ItemBrag)](https://poggit.pmmp.io/p/ItemBrag)
