<?php

namespace App\Action\Babyfoot;

use App\Entity\Player;

/**
 *
 */
class BabyfootCreateGameWSParams
{

    /**
     * @var Player
     */
    private $connectedUser;
    /**
     * @var int
     */
    private $redPlayerAttackId;

    /**
     * @var int
     */
    private $redPlayerDefenseId;

    /**
     * @var int
     */
    private $bluePlayerAttackId;

    /**
     * @var int
     */
    private $bluePlayerDefenseId;

    /**
     * @var int
     */
    private $mode;

    /**
     * @var int
     */
    private $modeLimitValue;

    /**
     * BabyfootCreateGameWSParams constructor.
     * @param Player $connectedUser
     * @param int $redPlayerAttack
     * @param int $redPlayerDefense
     * @param int $bluePlayerAttack
     * @param int $bluePlayerDefense
     * @param int $mode
     * @param int $modeLimitValue
     */
    public function __construct(Player $connectedUser, $redPlayerAttack, $redPlayerDefense,
                                $bluePlayerAttack, $bluePlayerDefense, $mode, $modeLimitValue)
    {
        $this->connectedUser = $connectedUser;
        $this->redPlayerAttackId = $redPlayerAttack;
        $this->redPlayerDefenseId = $redPlayerDefense;
        $this->bluePlayerAttackId = $bluePlayerAttack;
        $this->bluePlayerDefenseId = $bluePlayerDefense;
        $this->mode = $mode;
        $this->modeLimitValue = $modeLimitValue;
    }

    /**
     * @return Player
     */
    public function getConnectedUser()
    {
        return $this->connectedUser;
    }

    /**
     * @return int
     */
    public function getRedPlayerAttackId()
    {
        return $this->redPlayerAttackId;
    }

    /**
     * @return int
     */
    public function getRedPlayerDefenseId()
    {
        return $this->redPlayerDefenseId;
    }

    /**
     * @return int
     */
    public function getBluePlayerAttackId()
    {
        return $this->bluePlayerAttackId;
    }

    /**
     * @return int
     */
    public function getBluePlayerDefenseId()
    {
        return $this->bluePlayerDefenseId;
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return int
     */
    public function getModeLimitValue()
    {
        return $this->modeLimitValue;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->bluePlayerAttackId && $this->bluePlayerAttackId > 0
            && $this->bluePlayerDefenseId && $this->bluePlayerDefenseId > 0
            && $this->redPlayerAttackId && $this->redPlayerAttackId > 0
            && $this->redPlayerDefenseId && $this->redPlayerDefenseId > 0
            && $this->mode && $this->mode > 0
            && $this->modeLimitValue && $this->modeLimitValue > 0;
    }
}