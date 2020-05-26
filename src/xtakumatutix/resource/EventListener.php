<?php

namespace xtakumatutix\resource;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use onebone\economyapi\EconomyAPI;

class EventListener implements Listener 
{
    private $Main;

    public function __construct(Main $Main)
    {
        $this->Main = $Main;
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $block = $event->getBlock();
        $blockid = $block->getID();
        $blockdamage = $block->getDamage();
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $level = $player->getLevel()->getName();
        var_dump($level == "resource");
        if ($level == "resource")
            switch ($blockid) {
                case 87:
                case 49:
                case 121:
                case 155:
                case 45:
                case 98:
                case 12:
                case 3:
                case 17:
                case 1:
                    $this->set($blockid, $blockdamage, $x, $y, $z);

                    if (mt_rand(1, 10) === 2) {
                        $money = mt_rand(15, 220);
                        EconomyAPI::getInstance()->addMoney($name, $money);
                        $player->sendPopup("§aGet!! §f" . $money . "§6K§eG");
                    }
            }
    }




    public function set($blockid, $blockdamage, $x, $y, $z)
    {
        $setlevel = $this->Main->getServer()->getLevelByName("resource");
        $task = new ClosureTask(function (int $currentTick) use ($x, $y, $z, $blockid, $blockdamage, $setlevel): void {
            var_dump("!!");
            $setlevel->setBlockIdAt($x, $y, $z, $blockid);
            $setlevel->setBlockDataAt($x, $y, $z, $blockdamage);
        });
        $plugin = Server::getInstance()->getPluginManager()->getPlugin("Resource");
        /** @var Plugin $plugin */
        $plugin->getScheduler()->scheduleDelayedTask($task, 20);
    }
}