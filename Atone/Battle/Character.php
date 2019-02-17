<?php

namespace Atone\Battle;


abstract class Character
{
    const ATTACKER = 0,
        DEFENDER = 1;

    /** @var string */
    protected $name;

    /** @var int */
    protected $health;

    /** @var int */
    protected $strength;

    /** @var int */
    protected $defence;

    /** @var int */
    protected $speed;

    /** @var int */
    protected $luck;

    /**
     * Character constructor.
     * @param string $name
     * @param string $health
     * @param string $strength
     * @param string $defence
     * @param string $speed
     * @param string $luck
     */
    public function __construct(string $name, string $health, string $strength, string $defence, string $speed, string $luck)
    {
        $this->name = $name;
        $this->setHealth($this->setRandom($health));
        $this->setStrength($this->setRandom($strength));
        $this->setDefence($this->setRandom($defence));
        $this->setSpeed($this->setRandom($speed));
        $this->setLuck($this->setRandom($luck));
    }

    /**
     * @param Character $defender
     * @return int
     */
    public function attack(Character $defender)
    {
        if ($defender->isLucky()) {
            echo sprintf('%s got lucky and %s missed', $defender->getName(), $this->getName()) . PHP_EOL;
            return 0;
        }
        $damage = $this->strength - $defender->defence;
        if (property_exists(get_class($this), 'skills')) {
            $damage = $this->tryUseSkill($damage, Character::ATTACKER);
        }
        if (property_exists(get_class($defender), 'skills')) {
            $damage = $defender->tryUseSkill($damage, Character::DEFENDER);
        }
        $remaining = max($defender->getHealth() - $damage, 0);
        $defender->setHealth($remaining);
        return $damage;
    }

    /**
     * @return string
     */
    public function getName() :string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $health
     */
    public function setHealth(int $health): void
    {
        $this->health = $health;
    }

    /**
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     */
    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getDefence(): int
    {
        return $this->defence;
    }

    /**
     * @param int $defence
     */
    public function setDefence(int $defence): void
    {
        $this->defence = $defence;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }

    /**
     * @param int $luck
     */
    public function setLuck(int $luck): void
    {
        $this->luck = $luck;
    }

    protected function isLucky()
    {
        $random = mt_rand(0, 100);
        return $random <= $this->getLuck();
    }

    /**
     * @param string $range
     * @return int
     */
    protected function setRandom(string $range)
    {
        $minMax = explode('-', $range);
        $result = mt_rand((int)$minMax[0], (int)$minMax[1]);
        return $result;
    }

}