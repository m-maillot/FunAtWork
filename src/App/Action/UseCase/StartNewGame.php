<?php

namespace App\Action\UseCase;

use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootTeamResource;
use App\Resource\PlayerResource;

/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 5/6/17
 * Time: 8:33 AM
 */
class StartNewGame implements UseCase
{

    /**
     * @var TeamManagement
     */
    private $teamManagement;
    /**
     * @var BabyfootGameResource
     */
    private $gameResource;

    /**
     * StartNewGame constructor.
     * @param BabyfootTeamResource $teamResource
     * @param BabyfootGameResource $gameResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootTeamResource $teamResource, BabyfootGameResource $gameResource, PlayerResource $playerResource)
    {
        $this->teamManagement = new TeamManagement($teamResource, $playerResource);
        $this->gameResource = $gameResource;
    }


    /**
     * @param Player $creator
     * @param int $blueAttackId
     * @param int $blueDefenseId
     * @param int $redAttackId
     * @param int $redDefenseId
     * @return Response
     */
    public function execute(Player $creator, $blueAttackId, $blueDefenseId, $redAttackId, $redDefenseId)
    {
        if ($this->currentGame()) {
            return new Response(400, "Game is already running");
        }

        if ($this->teamManagement->detectSamePlayerInTeam($blueAttackId, $blueDefenseId, $redAttackId, $redDefenseId)) {
            return new Response(400, "Same player in each team");
        }

        $organizationId = $creator->getOrganization()->getId();

        // TODO  Check if other game is running for this organization, should deny request
        $redTeam = $this->teamManagement->createTeam($organizationId, $redAttackId, $redDefenseId);
        $blueTeam = $this->teamManagement->createTeam($organizationId, $blueAttackId, $blueDefenseId);

        if ($blueTeam && $redTeam) {
            // Create the game
            $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, $blueTeam, $redTeam,
                new \DateTime(), null, null, $creator, $creator->getOrganization(), null);
            $game = $this->gameResource->createOrUpdate($game);
            return new Response(200, "Game created", $game);
        }
        return new Response(500, "Failed to create teams");
    }


    /**
     * @return bool
     */
    private function currentGame()
    {
        // TODO Check there is no current running game
        return false;
    }
}