<?php
use App\Entity\Babyfoot\BabyfootGame;
use App\Entity\Babyfoot\BabyfootTeam;
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
     * @var BabyfootTeamResource
     */
    private $teamResource;
    /**
     * @var BabyfootGameResource
     */
    private $gameResource;
    /**
     * @var PlayerResource
     */
    private $playerResource;

    /**
     * StartNewGame constructor.
     * @param BabyfootTeamResource $teamResource
     * @param BabyfootGameResource $gameResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootTeamResource $teamResource, BabyfootGameResource $gameResource, PlayerResource $playerResource)
    {
        $this->teamResource = $teamResource;
        $this->gameResource = $gameResource;
        $this->playerResource = $playerResource;
    }


    /**
     * @param int $blueAttackId
     * @param int $blueDefenseId
     * @param int $redAttackId
     * @param int $redDefenseId
     * @return Response
     */
    public function execute($blueAttackId, $blueDefenseId, $redAttackId, $redDefenseId)
    {
        if ($this->currentGame()) {
            return new Response(400, "Game is already running");
        }

        if ($this->detectSamePlayerInTeam($blueAttackId, $blueDefenseId, $redAttackId, $redDefenseId)) {
            return new Response(400, "Same player in each team");
        }

        $blueAttack = $this->playerResource->selectOne($blueAttackId);
        $blueDefense = $this->playerResource->selectOne($blueDefenseId);
        $redAttack = $this->playerResource->selectOne($redAttackId);
        $redDefense = $this->playerResource->selectOne($redDefenseId);

        if (!$blueAttack && !$blueDefense && !$redAttack && !$redDefense) {
            return new Response(400, "Missing player");
        }


        // Check team is already exist, otherwise create a new one
        $blueTeam = $this->createTeamIfNoExist($blueAttack, $blueAttack);
        $redTeam = $this->createTeamIfNoExist($redAttack, $redDefense);

        if ($blueTeam && $redTeam) {
            // Create the game
            $game = new BabyfootGame(0, BabyfootGame::GAME_STARTED, $blueTeam, $redTeam, new \DateTime(), new \DateTime());
            $game = $this->gameResource->createOrUpdate($game);
            return new Response(200, "Game created", $game);
        }
        return new Response(500, "Failed to create teams (" . $blueAttack->getName() . '-' . $blueDefense->getName() . ' vs ' . $redAttack->getName() . '-' . $redDefense->getName() . ')');
    }

    /**
     * @param int $blueAttackId
     * @param int $blueDefenseId
     * @param int $redAttackId
     * @param int $redDefenseId
     * @return bool
     */
    public function detectSamePlayerInTeam($blueAttackId, $blueDefenseId, $redAttackId, $redDefenseId)
    {
        return $blueAttackId == $redAttackId || $blueAttackId == $redDefenseId
            || $blueDefenseId == $redAttackId || $blueDefenseId == $redDefenseId;
    }

    /**
     * @param Player $attack
     * @param Player $defense
     * @return BabyfootTeam
     */
    private function createTeamIfNoExist(Player $attack, Player $defense)
    {
        $team = $this->teamResource->selectByPlayers($attack, $defense);

        if (!$team) {
            $team = $this->teamResource->create(new BabyfootTeam(0, $attack, $defense));
        }

        return $team;
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