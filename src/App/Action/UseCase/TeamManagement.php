<?php
/**
 * Created by IntelliJ IDEA.
 * User: mmaillot
 * Date: 9/18/17
 * Time: 5:11 PM
 */

namespace App\Action\UseCase;


use App\Entity\Babyfoot\BabyfootTeam;
use App\Entity\Player;
use App\Resource\Babyfoot\BabyfootTeamResource;
use App\Resource\PlayerResource;

class TeamManagement
{

    /**
     * @var BabyfootTeamResource
     */
    private $teamResource;
    /**
     * @var PlayerResource
     */
    private $playerResource;

    /**
     * TeamManagement constructor.
     * @param BabyfootTeamResource $teamResource
     * @param PlayerResource $playerResource
     */
    public function __construct(BabyfootTeamResource $teamResource, PlayerResource $playerResource)
    {
        $this->teamResource = $teamResource;
        $this->playerResource = $playerResource;
    }


    /**
     * @param int $organizationId
     * @param int $playerAttackId
     * @param int $playerDefenseId
     * @return BabyfootTeam|null
     */
    public function createTeam($organizationId, $playerAttackId, $playerDefenseId) {
        $playerAttack = $this->playerResource->selectOne($playerAttackId, $organizationId);
        $playerDefense = $this->playerResource->selectOne($playerDefenseId, $organizationId);
        if (!$playerAttack || !$playerDefense) {
            return null;
        }
        return $this->createTeamIfNoExist($playerAttack, $playerDefense);
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
            $team = $this->teamResource->create(new BabyfootTeam(0, $attack, $defense, $attack->getOrganization()));
        }

        return $team;
    }
}