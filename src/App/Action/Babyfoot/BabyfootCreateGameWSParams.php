<?php

namespace App\Action\Babyfoot;

/**
 *
 */
class BabyfootCreateGameWSParams
{

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
     * BabyfootCreateGameWSParams constructor.
     * @param $redPlayerAttack int
     * @param $redPlayerDefense int
     * @param $bluePlayerAttack int
     * @param $bluePlayerDefense int
     */
    public function __construct($redPlayerAttack, $redPlayerDefense, $bluePlayerAttack, $bluePlayerDefense)
    {
        $this->redPlayerAttackId = $redPlayerAttack;
        $this->redPlayerDefenseId = $redPlayerDefense;
        $this->bluePlayerAttackId = $bluePlayerAttack;
        $this->bluePlayerDefenseId = $bluePlayerDefense;
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
     * @return bool
     */
    public function isValid()
    {
        return $this->bluePlayerAttackId && $this->bluePlayerAttackId > 0
            && $this->bluePlayerDefenseId && $this->bluePlayerDefenseId > 0
            && $this->redPlayerAttackId && $this->redPlayerAttackId > 0
            && $this->redPlayerDefenseId && $this->redPlayerDefenseId > 0;
    }
}