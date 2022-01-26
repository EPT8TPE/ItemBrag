<?php

declare(strict_types=1);

namespace TPE\ItemBrag;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\item\enchantment\EnchantmentInstance;

class Main extends PluginBase implements Listener {

    public $coolDownList = [];

    // I haven't updated these for piggy ces, if anyone would like to, be my guest.
    private $myEnchants = [0 => "Protection",
        1 => "Fire_protection",
        2 => "Feather_falling",
        3 => "Blast_projection",
        4 => "Projectile_projection",
        5 => "thorns",
        6 => "Respiration",
        7 => "Depth_Strider",
        8 => "Aqua_affinity",
        9 => "Sharpness",
        10 => "Smite",
        11 => "Bane_of_arthropods",
        12 => "Knockback",
        13 => "Fire_aspect",
        14 => "Looting",
        15 => "Efficiency",
        16 => "Silk_touch",
        17 => "Unbreaking",
        18 => "Fortune",
        19 => "Power",
        20 => "Punch",
        21 => "Flame",
        22 => "Infinity",
        23 => "Luck_of_the_sea",
        24 => "Lure",
        108 => "Autorepair",
        118 => "SoulBound",
        114 => "Aerial",
        122 => "Backstab",
        120 => "Blessed",
        101 => "Blind",
        113 => "Charge",
        109 => "Cripple",
        102 => "Deathbringer",
        112 => "DeepWounds",
        117 => "Disarming",
        121 => "Disarmor",
        103 => "Gooey",
        119 => "Hallucination",
        116 => "Headless", 
        106 => "IceAspect", 
        100 => "Lifesteal",
        123 => "Lightening",
        124 => "LuckyCharm",
        104 => "Poison",
        107 => "Shockwave",
        111 => "Vampire",
        115 => "Wither",
        306 => "Autoaim",
        311 => "Blaze",
        300 => "Bombardment",
        309 => "Bountyhunter",
        302 => "Firework", 
        313 => "Grappling",
        312 => "Headhunter",
        310 => "Healing",
        316 => "Homing",
        315 => "Missile",
        304 => "Molotov",
        303 => "Paralyze",
        307 => "Piercing",
        314 => "Porkified",
        308 => "Shuffle",
        305 => "Volley",
        301 => "Witherskull",
        206 => "Driller",
        202 => "Energizing",
        200 => "Explosive",
        207 => "Haste",
        212 => "Jackpot",
        211 => "Oxygenate",
        203 => "Quickening",
        201 => "Smelting",
        205 => "Telepathy",
        204 => "Lumberjack",
        209 => "Farmer",
        208 => "Fertilizer",
        210 => "Harvest",
        400 => "Molten",
        401 => "Enlighted",
        402 => "Hardened",
        403 => "Poisoned",
        404 => "Frozen",
        405 => "Obsidianshield",
        406 => "Revulsion",
        407 => "Selfdestruct",
        408 => "Cursed",
        409 => "Endershift",
        410 => "Drunk",
        411 => "Berserker",
        412 => "Cloaking",
        413 => "Revive",
        414 => "Shrink",
        415 => "Grow",
        416 => "Cactus",
        417 => "Antiknockback",
        418 => "Forcefield",
        419 => "Overload",
        420 => "Armored",
        421 => "Tank",
        422 => "Heavy",
        423 => "Shielded",
        424 => "Poisonouscloud",
        604 => "Antitoxin",
        603 => "Focused",
        601 => "Glowing",
        600 => "Implants",
        602 => "Meditation",
        801 => "Chicken",
        804 => "Enraged",
        800 => "Parachute",
        802 => "Prowl",
        803 => "Spider",
        805 => "Vacuum",
        500 => "Gears",
        503 => "Jetpack",
        504 => "Magmawalker",
        501 => "Springs",
        502 => "Stomp",
        700 => "Radar"];

    public $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->config = $this->getConfig();
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
          if($this->config->get("brag-disabled") == false) {
            if($command->getName() == "brag") {
                if($sender instanceof Player) {

                    if ($sender->hasPermission("itembrag.brag.allow")) {

                        if(!isset($this->coolDownList[$sender->getName()])) {
                            $this->coolDownList[$sender->getName()] = time() + $this->config->get("brag-cooldown-time");

                            $itemb = $sender->getInventory()->getItemInHand();

                            if($itemb->isNull()) {
                                $sender->sendMessage($this->config->get("message-sent-to-player-when-hand-is-empty"));
                                return false;

                            }

                            $item = $sender->getInventory()->getItemInHand()->getName();
                            $player = $sender->getName();
                            $itemc = $sender->getInventory()->getItemInHand()->getCount();

                            $enchantmentNames = array_map(function(EnchantmentInstance $enchantment) : string{
                                $translation = $this->myEnchants[$enchantment->getType()->getId()] ?? "";
                                if($translation !== ""){
                                    return $this->getServer()->getLanguage()->translateString($translation);
                                }

                                return $enchantment->getType()->getName();
                            }, $itemb->getEnchantments());

                            $itemb->getEnchantments();
                            $enchantmentLevels = array_map(function(EnchantmentInstance $enchantment) : int{
                                return $enchantment->getLevel();

                            },$itemb->getEnchantments());
                            $newArray = [];

                            for($i = 0; $i < count($enchantmentNames); $i++) {
                                $newArray[$i] = [$enchantmentNames[$i], $enchantmentLevels[$i]];
                            }

                            $message = TextFormat::YELLOW . "Enchants: ";
                            for($i = 0; $i < count($newArray); $i++) {
                                $message .= TextFormat::LIGHT_PURPLE . " {$newArray[$i][0]}: {$newArray[$i][1]}, ";
                            }

                            if($itemb->hasEnchantments()) {
                                if($sender->hasPermission("itembrag.allow.enchants")){
                                    $this->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . TextFormat::ITALIC . "BRAG " . TextFormat::RESET . TextFormat::AQUA . TextFormat::BOLD . "$player " . TextFormat::RESET . TextFormat::GREEN . "is bragging about " . TextFormat::BOLD . TextFormat::GREEN . "X" . TextFormat::RESET . TextFormat::GREEN . "$itemc " . TextFormat::RESET . TextFormat::GREEN . "of " . TextFormat::RESET . TextFormat::BOLD . "$item" . "\n" . TextFormat::RESET . "$message");

                                } else {
                                    $sender->sendMessage($this->config->get("no-perms-message-enchants"));
                                }
                            }
                            if(!$itemb->hasEnchantments()) {
                                $this->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . TextFormat::ITALIC . "BRAG " . TextFormat::RESET . TextFormat::AQUA . TextFormat::BOLD . "$player " . TextFormat::RESET . TextFormat::GREEN . "is bragging about " . TextFormat::BOLD . TextFormat::GREEN . "X" . TextFormat::RESET . TextFormat::GREEN . "$itemc " . TextFormat::RESET . TextFormat::GREEN . "of " . TextFormat::RESET . TextFormat::BOLD . "$item");
                            }

                        } else {
                            if(time() < $this->coolDownList[$sender->getName()]) {
                                $remaining = $this->coolDownList[$sender->getName()] - time();

                                $sender->sendMessage(TextFormat::RED . "This command is on cooldown for the next " . $remaining . " seconds!");
                            } else {
                                unset($this->coolDownList[$sender->getName()]);
                            }

                        }

                    } else {
                        $sender->sendMessage($this->config->get("no-perms-message"));
                    }

                } else {
                    $sender->sendMessage("You can not run this command via console");
                }

            }

        } else {
            $sender->sendMessage($this->config->get("brag-feature-disabled-message"));
        }

          return true;
    }

}
