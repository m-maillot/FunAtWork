<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_game")
 */
class BabyfootGame
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @ORM\JoinColumn(name="local_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    private $LocalTeam;

    /**
     * @ORM\ManyToOne(targetEntity="BabyfootTeam", fetch="EAGER")
     * @ORM\JoinColumn(name="visitor_team_id", referencedColumnName="id")
     * @var BabyfootTeam
     **/
    private $VisitorTeam;

    /**
     * @ORM\OneToMany(targetEntity="BabyfootGoal", mappedBy="game")
     */
    private $goals;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $startedDate;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $endedDate;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return BabyfootTeam
     */
    public function getLocalTeam()
    {
        return $this->LocalTeam;
    }

    /**
     * @return BabyfootTeam
     */
    public function getVisitorTeam()
    {
        return $this->VisitorTeam;
    }

    /**
     * @return mixed
     */
    public function getGoals()
    {
        return $this->goals;
    }

    /**
     * @return \DateTime
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndedDate()
    {
        return $this->endedDate;
    }

    public function expose()
    {
        $var = get_object_vars($this);
        foreach ($var as &$value) {
            if (is_object($value) && method_exists($value, 'expose')) {
                $value = $value->expose();
            }
        }
        return $var;
    }
}