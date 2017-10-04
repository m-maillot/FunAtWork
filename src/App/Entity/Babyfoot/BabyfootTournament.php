<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 2:31 PM
 */

namespace App\Entity\Babyfoot;

use App\Entity\Organization;
use Doctrine\ORM\PersistentCollection;

/**
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="babyfoot_tournament")
 */
class BabyfootTournament
{

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Doctrine\ORM\Mapping\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime")
     * @var \DateTime
     */
    protected $startedDate;

    /**
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $endedDate;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Organization", fetch="EAGER")
     * @Doctrine\ORM\Mapping\JoinColumn(name="organization_id", referencedColumnName="id", nullable=false)
     * @var Organization
     */
    protected $organization;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="BabyfootGameKnockout", mappedBy="tournament", fetch="EAGER")
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