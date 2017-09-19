<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/13/17
 * Time: 5:34 PM
 */

namespace App\Action\Player;


use App\Action\Babyfoot\Model\TournamentGameInitial;
use App\Action\Babyfoot\Model\TournamentGameKnockout;
use App\Entity\Organization;

class CreateTournamentWSParams
{

    /**
     * @var int
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
     * @var TournamentGameInitial[]
     */
    private $initalGames;

    /**
     * @var TournamentGameKnockout[]
     */
    private $knockoutGames;

    /**
     * CreateTournamentParams constructor.
     * @param int $startedDate
     * @param string $name
     * @param Organization $organisation
     * @param TournamentGameInitial[] $initialGames
     * @param TournamentGameKnockout[] $knockoutGames
     */
    public function __construct($startedDate, $name, Organization $organisation, array $initialGames, array $knockoutGames)
    {
        $this->startedDate = $startedDate;
        $this->name = $name;
        $this->organisation = $organisation;
        $this->initalGames = $initialGames;
        $this->knockoutGames = $knockoutGames;
    }

    /**
     * @return int
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
     * @return TournamentGameInitial[]
     */
    public function getInitalGames()
    {
        return $this->initalGames;
    }

    /**
     * @return TournamentGameKnockout[]
     */
    public function getKnockoutGames()
    {
        return $this->knockoutGames;
    }

}