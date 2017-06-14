<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class BabyfootAddGoalConnectedWSParams
{

    /**
     * 1 = red team, 2 = blue team
     * @var int
     */
    private $team;

    /**
     * 1 = attack, 2 = defense
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $gamelle;

    /**
     * BabyfootAddGoalWSParams constructor.
     * @param int $team // 0 = red, 1 = blue
     * @param int $position //
     * @param bool $gamelle
     */
    public function __construct($team, $position, $gamelle)
    {
        $this->team = $team;
        $this->position = $position;
        $this->gamelle = $gamelle;
    }

    /**
     * @return boolean
     */
    public function isBlueTeam()
    {
        return $this->team == 2;
    }

    /**
     * @return boolean
     */
    public function isRedTeam()
    {
        return $this->team == 1;
    }

    /**
     * @return boolean
     */
    public function isAttack()
    {
        return $this->position == 1;
    }

    /**
     * @return boolean
     */
    public function isDefense()
    {
        return $this->position == 2;
    }

    /**
     * @return bool
     */
    public function isGamelle()
    {
        return $this->gamelle;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->team && ($this->team == 1 || $this->team == 2)
            && $this->position && ($this->position == 1 || $this->position == 2);
    }
}