<?php

namespace Atone\Battle;


class Hero extends Character
{
    const
        KEY_CHANCE      = 'chance',
        KEY_DEFENDER    = 'defender',
        KEY_MULTIPLIER  = 'multiplier';

    /**
     * @var array
     */
    protected $skills = [];

    /**
     * Hero constructor.
     * @param string $name
     * @param string $health
     * @param string $strength
     * @param string $defence
     * @param string $speed
     * @param string $luck
     * @param array $skills
     */
    public function __construct(
        string $name,
        string $health,
        string $strength,
        string $defence,
        string $speed,
        string $luck,
        array $skills)
    {
        parent::__construct($name, $health, $strength, $defence, $speed, $luck, $skills);
        $this->skills = $skills;
    }

    /**
     * @param int $damage
     * @param int $attackerDefender
     * @return int
     */
    public function tryUseSkill(int $damage, int $attackerDefender)
    {
        foreach ($this->skills as $skillName => $skill) {
            if ($skill[self::KEY_DEFENDER] == $attackerDefender) {
                $random = mt_rand(0, 100);
                if ($random <= $skill[self::KEY_CHANCE]) {
                    $damage *= $skill[self::KEY_MULTIPLIER];
                    echo sprintf('%s is using %s', $this->getName(), $skillName) . PHP_EOL;
                }
            }
        }
        return $damage;
    }

}