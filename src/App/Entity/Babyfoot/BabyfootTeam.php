<?php

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use App\Entity\Player;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="babyfoot_team")
 */
class BabyfootTeam
{

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="attack_player_id", referencedColumnName="id")
     * @var Player
     **/
    protected $playerAttack;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Player", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="defense_player_id", referencedColumnName="id")
     * @var Player
     **/
    protected $playerDefense;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="organization_id", referencedColumnName="id")
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