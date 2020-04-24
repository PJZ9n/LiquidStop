<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of LiquidStop.
 *
 * LiquidStop is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * LiquidStop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with LiquidStop.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PJZ9n\LiquidStop;

use Particle\Validator\Failure;
use Particle\Validator\Validator;
use pocketmine\block\BlockFactory;
use pocketmine\block\Lava;
use pocketmine\block\Water;
use pocketmine\plugin\PluginBase;
use RuntimeException;

require_once __DIR__ . "/../../../vendor/autoload.php";

class LiquidStop extends PluginBase
{
    
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        
        $validator = new Validator();
        $validator->required("stop-water")->bool();
        $validator->required("stop-lava")->bool();
        $validateResult = $validator->validate($this->getConfig()->getAll());
        if ($validateResult->isNotValid()) {
            $messages = array_map(function (Failure $failure): string {
                return $failure->format();
            }, $validateResult->getFailures());
            throw new RuntimeException("Invalid configuration file: " . implode($messages, " | "));
        }
        
        if ($this->getConfig()->get("stop-water")) {
            BlockFactory::registerBlock(new class () extends Water {
                public function onScheduledUpdate(): void
                {
                    //Stop
                }
            }, true);
        }
        if ($this->getConfig()->get("stop-lava")) {
            BlockFactory::registerBlock(new class() extends Lava {
                public function onScheduledUpdate(): void
                {
                    //Stop
                }
            }, true);
        }
    }
}