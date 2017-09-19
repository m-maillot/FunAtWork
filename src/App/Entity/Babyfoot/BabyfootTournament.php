<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 2:31 PM
 */

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="babyfoot_tournament")
 */
class BabyfootTournament
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    protected $startedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $endedDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @var Organization
     */
    protected $organization;

    /**
     * @ORM\OneToMany(targetEntity="BabyfootGameKnockout", mappedBy="tournament", fetch="EAGER")
     * @var PersistentCollection
     */
    protected $games;

    /**
     * BabyfootTournament constructor.
     * @param int $id
     * @param int $type
     * @param string $name
     * @param \DateTime $startedDate
     * @param \DateTime $endedDate
     * @param Organization $organization
     */
    public function __construct($id, $type, $name, \DateTime $startedDate, \DateTime $endedDate = null, Organization $organization)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->startedDate = $startedDate;
        $this->endedDate = $endedDate;
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
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return PersistentCollection
     */
    public function getGames()
    {
        return $this->games;
    }
}