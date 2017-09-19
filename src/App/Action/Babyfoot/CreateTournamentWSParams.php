<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:34 PM
 */

namespace App\Action\Babyfoot;


use App\Action\Babyfoot\Model\TournamentGame;
use App\Entity\Organization;

class CreateTournamentWSParams
{

    /**
     * @var \DateTime
     */
    private $startedDate;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Organization
     */
    private $organisation;

    /**
     * @var TournamentGame[]
     */
    private $games;

    /**
     * CreateTournamentParams constructor.
     * @param \DateTime $startedDate
     * @param string $name
     * @param Organization $organisation
     * @param TournamentGame[] $games
     */
    public function __construct($startedDate, $name, Organization $organisation, array $games)
    {
        $this->startedDate = $startedDate;
        $this->name = $name;
        $this->organisation = $organisation;
        $this->games = $games;
    }

    /**
     * @return \DateTime
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Organization
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @return TournamentGame[]
     */
    public function getGames()
    {
        return $this->games;
    }

}