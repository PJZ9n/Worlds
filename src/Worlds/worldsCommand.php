<?php

    namespace Worlds;


    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\plugin\Plugin;

    class worldsCommand extends Command
    {

        private $plugin;

        public function __construct(Plugin $plugin)
        {
            $this->plugin = $plugin;
            parent::__construct("worlds", "Worldsのメインコマンド", "/worlds");
            $this->setPermission("worlds.command.worlds");
        }

        public function execute(CommandSender $sender, string $commandLabel, array $args): bool
        {
            if (!$sender->isOp()) {
                $sender->sendMessage("§l§4[ERROR]§rこのコマンドを実行する権限がありません");
                return true;
            }
            if (!isset($args[0])) {
                $sender->sendMessage("§l§4[ERROR]§rコマンドの記法が間違っています");
                $this->sendCommandList($sender);
                return true;
            }
            switch (strtolower($args[0])) {
                case "generate":
                    if (isset($args[1])) {
                        if ($this->plugin->getServer()->getLevelByName($args[1]) === null) {
                            $this->plugin->getServer()->generateLevel($args[1]);
                            $sender->sendMessage("ワールド {$args[1]} を生成しました");
                            return true;
                        } else {
                            $sender->sendMessage("§l§4[ERROR]§rすでにワールドが存在しているため生成できません");
                            return true;
                        }
                    } else {
                        $sender->sendMessage("§l§4[ERROR]§rコマンドの記法が間違っています");
                        $this->sendCommandList($sender);
                        return true;
                    }
                case "teleport":
                    if ($sender instanceof Player) {
                        if (isset($args[1])) {
                            if ($this->plugin->getServer()->getLevelByName($args[1]) !== null) {
                                $sender->teleport($this->plugin->getServer()->getLevelByName($args[1])->getSafeSpawn());
                                $sender->sendMessage("ワールド {$args[1]} に移動しました");
                                return true;
                            } else {
                                $sender->sendMessage("§l§4[ERROR]§rワールド {$args[1]} は存在しないため移動できません");
                                return true;
                            }
                        } else {
                            $sender->sendMessage("§l§4[ERROR]§rコマンドの記法が間違っています");
                            $this->sendCommandList($sender);
                            return true;
                        }
                    } else {
                        $sender->sendMessage("§l§4[ERROR]§rこのコマンドはプレイヤーのみ実行できます");
                        return true;
                    }
                case "mode":
                    $sender->sendMessage("現在のワールド生成モードは {$this->plugin->getServer()->getLevelType()} です");
                    return true;
                case "list":
                    $sender->sendMessage("---ワールドリスト---");
                    foreach ($this->plugin->getServer()->getLevels() as $level) {
                        $sender->sendMessage("-{$level->getName()}");
                    }
                    return true;
                default:
                    $sender->sendMessage("§l§4[ERROR]§rコマンドの記法が間違っています");
                    $this->sendCommandList($sender);
                    return true;
            }
        }

        private function sendCommandList(CommandSender $sender)
        {
            $sender->sendMessage("---コマンドリスト---");
            $sender->sendMessage("-/worlds generate < ワールド名 > : ワールドを生成する");
            $sender->sendMessage("-/worlds teleport < ワールド名 > : ワールドに移動する");
            $sender->sendMessage("-/worlds mode : ワールドの生成モードを確認する");
            $sender->sendMessage("-/worlds list : ワールドのリストを確認する");
        }

    }