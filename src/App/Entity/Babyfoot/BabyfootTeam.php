<?php

namespace App\Entity\Babyfoot;

use App\Entity\Player;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_team")
 */
class BabyfootTeam
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @ORM\JoinColumn(name="attack_player_id", referencedColumnName="id")
     * @var Player
     **/
    protected $playerAttack;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @ORM\JoinColumn(name="defense_player_id", referencedColumnName="id")
     * @var Player
     **/
    protected $playerDefense;

    /**
     * Team constructor.
     * @param int $id
     * @param Player $player1
     * @param Player $player2
     */
    public function __construct($id, Player $player1, Player $player2)
    {
        $this->id = $id;
        $this->playerAttack = $player1;
        $this->playerDefense = $player2;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Player
     */
    public function getPlayerAttack()
    {
        return $this->playerAttack;
    }

    /**
     * @return Player
     */
    public function getPlayerDefense()
    {
        return $this->playerDefense;
    }
}