<?php

namespace Atone\Battle;


class Beast extends Character
{
    /**
     * Beast constructor.
     * @param string $name
     * @param string $health
     * @param string $strength
     * @param string $defence
     * @param string $speed
     * @param string $luck
     */
    public function __construct(string $name, string $health, string $strength, string $defence, string $speed, string $luck)
    {
        parent::__construct($name, $health, $strength, $defence, $speed, $luck);
    }

}