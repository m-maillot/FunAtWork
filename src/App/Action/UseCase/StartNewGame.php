<?php

namespace App\Action\UseCase;

use App\Action\Babyfoot\BabyfootCreateGameWSParams;
use App\Entity\Babyfoot\BabyfootGame;
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
     * @param BabyfootCreateGameWSParams $params
     * @return Response
     */
    public function execute(BabyfootCreateGameWSParams $params)
    {
        $organizationId = $params->getConnectedUser()->getOrganization()->getId();

        if ($this->currentGame($organizationId) != null) {
            return new Response(400, "Game is already running");
        }

        if ($this->teamManagement->detectSamePlayerInTeam($params->getBluePlayerAttackId(), $params->getBluePlayerDefenseId(),
            $params->getRedPlayerAttackId(), $params->getRedPlayerDefenseId())
        ) {
            return new Response(400, "Same player in each team");
        }

        $redTeam = $this->teamManagement->createTeam($organizationId, $params->getRedPlayerAttackId(), $params->getRedPlayerDefenseId());
        $blueTeam = $this->teamManagement->createTeam($organizationId, $params->getBluePlayerAttackId(), $params->getBluePlayerDefenseId());

        if ($blueTeam && $redTeam) {
            // Create the game
            $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, $blueTeam, $redTeam,
                $params->getMode(), $params->getModeLimitValue(), new \DateTime(), null, null,
                $params->getConnectedUser(), $params->getConnectedUser()->getOrganization(), null);
            $game = $this->gameResource->createOrUpdate($game);
            return new Response(200, "Game created", $game);
        }
        return new Response(500, "Failed to create teams");
    }


    /**
     * @param $organizationId int
     * @return BabyfootGame|null
     */
    private function currentGame($organizationId)
    {
        return $this->gameResource->selectCurrent($organizationId);
    }
}