<?php

    namespace Worlds;

    use pocketmine\plugin\PluginBase;

    class main extends PluginBase
    {
        public function onEnable()
        {
            $this->getLogger()->info("{$this->getDescription()->getName()} {$this->getDescription()->getVersion()} が読み込まれました");
            $this->getServer()->getCommandMap()->register("worlds", new worldsCommand($this));
            foreach (scandir($this->getServer()->getDataPath() . "worlds/") as $index => $folder_name) {
                if ($index >= 2) {
                    $this->getLogger()->info("ワールド {$folder_name} を読み込みます");
                    $this->getServer()->loadLevel($folder_name);
                }
            }
        }

        public function onDisable()
        {
            $this->getLogger()->info("{$this->getDescription()->getName()} {$this->getDescription()->getVersion()} が終了しました");
        }
    }