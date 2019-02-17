<?php

namespace Atone\Battle;


class Battle
{
    const
        HERO        = 'Hero',
        BEAST       = 'Beast',
        NAME        = 'Name',
        STATS       = 'Stats',
        SKILLS      = 'Skills',
        HEALTH      = 'Health',
        STRENGTH    = 'Strength',
        DEFENCE     = 'Defence',
        SPEED       = 'Speed',
        LUCK        = 'Luck';

    const SWOT = [
        self::HEALTH,
        self::STRENGTH,
        self::DEFENCE,
        self::SPEED,
        self::LUCK,
    ];

    /** @var array */
    private $settings;

    /** @var Hero */
    private $hero;

    /** @var Beast */
    private $beast;

    /**
     * @var array
     */
    private $attackerDefender = [];

    /**
     * @var int
     */
    private $turn;

    /**
     * Battle constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $heroStats = $this->settings[self::HERO][self::STATS];
        $heroSkills = array_diff_key($settings[self::HERO], [self::NAME => '', self::STATS => '']);
        $this->hero = new Hero(
            $this->settings[self::HERO][self::NAME],
            $heroStats[self::HEALTH],
            $heroStats[self::STRENGTH],
            $heroStats[self::DEFENCE],
            $heroStats[self::SPEED],
            $heroStats[self::LUCK],
            $heroSkills
        );
        $beastStats = $this->settings[self::BEAST][self::STATS];
        $this->beast = new Beast(
            $this->settings[self::BEAST][self::NAME],
            $beastStats[self::HEALTH],
            $beastStats[self::STRENGTH],
            $beastStats[self::DEFENCE],
            $beastStats[self::SPEED],
            $beastStats[self::LUCK]
        );
        $this->turn = 0;
    }

    public function dispatch()
    {
        $this->setAttackerDefender();
        $this->tellStory();
        while (
            $this->getDefender()->getHealth() > 0
            && $this->turn < 20
        ) {
            $this->turn++;
            $this->logLine('');
            $this->logRound();
            $damage = $this->getAttacker()->attack($this->getDefender());
            $this->logDamage($damage);
            if ($this->getDefender()->getHealth() > 0)  {
                $this->attackerDefender = array_reverse($this->attackerDefender);
            }
        }
        if ($this->turn == 20) {
            $winner = $this->getDefender()->getHealth() >= $this->getAttacker()->getHealth()
                ? $this->getDefender()
                : $this->getAttacker();
            $line = sprintf('%s won!', $winner->getName());
        } else {
            $line = sprintf('%s won!', $this->getAttacker()->getName());
        }
        $this->logLine($line);
    }

    private function setAttackerDefender()
    {
        $this->attackerDefender = [
            $this->hero,
            $this->beast
        ];
        if ($this->beast->getSpeed() > $this->hero->getSpeed()) {
            $this->attackerDefender = array_reverse($this->attackerDefender);
        } else if ($this->beast->getLuck() >= $this->hero->getLuck()) {
            $this->attackerDefender = array_reverse($this->attackerDefender);
        }
    }

    private function tellStory()
    {
        foreach ($this->settings['Story']['Lines'] as $line) {
            $this->logLine($line);
        }
    }

    /**
     * @return Character
     */
    private function getAttacker()
    {
        return $this->attackerDefender[0];
    }

    /**
     * @return Character
     */
    private function getDefender()
    {
        return $this->attackerDefender[1];
    }

    private function logRound()
    {
        echo $this->logLine($this->getAttacker()->getName() . ' attacks!');
        foreach ($this->attackerDefender as $character) {
            $line = $this->getSwot($character);
            $this->logLine($line);
        }
    }

    private function logDamage(int $damage)
    {
        if (!$damage) {
            return;
        }
        /** @var Character $defender */
        $defender  = $this->getDefender();
        $line = sprintf('%s took %d damage (%s left: %d)',
            $defender->getName(),
            $damage,
            self::HEALTH,
            $defender->getHealth()
        );
        $this->logLine($line);
    }

    private function getSwot(Character $character)
    {
        $line = sprintf('%s : ', $character->getName());
        foreach (self::SWOT as $stat) {
            $methodName = sprintf('get%s', $stat);
            $line .= sprintf('%s: %s, ', $stat, $character->$methodName());
        }
        return $line;
    }

    private function logLine(string $line)
    {
        sleep(1);
        echo $line . PHP_EOL;
    }
}