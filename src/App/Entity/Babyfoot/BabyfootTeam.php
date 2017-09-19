<?php

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use App\Entity\Player;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * @var Organization
     */
    protected $organization;

    /**
     * BabyfootTeam constructor.
     * @param int $id
     * @param Player $playerAttack
     * @param Player $playerDefense
     * @param Organization $organization
     */
    public function __construct($id, Player $playerAttack, Player $playerDefense, Organization $organization)
    {
        $this->id = $id;
        $this->playerAttack = $playerAttack;
        $this->playerDefense = $playerDefense;
        $this->organization = $organization;
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

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}