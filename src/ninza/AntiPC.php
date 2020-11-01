<?php

namespace VintageLamb;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\LoginPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class AntiPC extends PluginBase implements Listener {

  public function onEnable(){
     $this->getServer()->getPluginManager()->registerEvents($this, $this);
     @mkdir($this->getDataFolder());
   	$this->cfg = new Config($this->getDataFolder()."players.yml", Config::YAML);
  }

  public function onCommand( CommandSender $s, Command $cmd, $label, array $args ){ 
		if($cmd == "license"){
       if(!isset($args[0])){
          $s->sendMessage("§l§c> §7Использование: §e/license <ник>");
       }
       $nick = strtolower($args[0]);
       if($this->cfg->get($nick) == 1){
          $this->cfg->remove($nick);
          $this->cfg->save();
          $player->sendMessage("§c§l> §7Игрок §e{$nick} §7теперь не может заходить с пк!");
       }else{
         $this->cfg->set($nick, 1);
         $this->cfg->save();
         $player->sendMessage("§c§l> §7Игрок §e{$nick} §7теперь может заходить с пк!");
      }
   }
}

  public function DataPacketReceive(DataPacketReceiveEvent $event){
     $player = $event->getPlayer();
     $packet = $event->getPacket();
     $nick = strtolower($player->getName());
     if($packet instanceof LoginPacket){
        if($packet->clientData["DeviceOS"] == 7){
           if($this->cfg->get($nick) != 1){
             $player->close("§c§l> §7Нельзя заходить с §ePC§7!\n§c§l> §7Купи пропуск на сайте и играй с §ePC§7!");
           }
        }
     }
  }
}