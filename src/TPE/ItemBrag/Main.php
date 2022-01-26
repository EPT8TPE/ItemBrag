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

    private $config;

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
                                return $enchantment->getType()->getName();
                            }, $itemb->getEnchantments());

                            $enchantmentLevels = array_map(function(EnchantmentInstance $enchantment) : int{
                                return $enchantment->getLevel();
                            }, $itemb->getEnchantments());
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
