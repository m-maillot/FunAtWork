<?php
// DIC configuration

use App\Action\BabyfootAction;
use App\Action\BabyfootTournamentAction;
use App\Action\OrganizationAction;
use App\Action\PlayerAction;
use App\Resource\Babyfoot\BabyfootGameKnockoutResource;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\Babyfoot\BabyfootTeamResource;
use App\Resource\Babyfoot\BabyfootTournamentResource;
use App\Resource\OrganizationResource;
use App\Resource\PlayerResource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

$container = $app->getContainer();

// monolog
/**
 * @param $c ContainerInterface
 * @return \Monolog\Logger
 */
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Doctrine
/**
 * @param $c ContainerInterface
 * @return EntityManager
 */
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $config = Setup::createAnnotationMetadataConfiguration(
        $settings['doctrine']['meta']['entity_path'],
        $settings['doctrine']['meta']['auto_generate_proxies'],
        $settings['doctrine']['meta']['proxy_dir'],
        $settings['doctrine']['meta']['cache'],
        false
    );
    return EntityManager::create($settings['doctrine']['connection'], $config);
};

/**
 * @param $c ContainerInterface
 * @return PlayerAction
 */
$container['App\Action\PlayerAction'] = function ($c) {
    $playerResource = new PlayerResource($c->get('em'));
    $organizationResource = new OrganizationResource($c->get('em'));
    return new PlayerAction($playerResource, $organizationResource);
};

/**
 * @param $c ContainerInterface
 * @return BabyfootAction
 */
$container['App\Action\BabyfootAction'] = function ($c) {
    $playerResource = new PlayerResource($c->get('em'));
    $gameResource = new BabyfootGameResource($c->get('em'));
    $goalResource = new BabyfootGoalResource($c->get('em'));
    $teamResource = new BabyfootTeamResource($c->get('em'));
    $knockoutResource = new BabyfootGameKnockoutResource($c->get('em'));
    return new BabyfootAction($c->get('logger'), $teamResource, $gameResource, $goalResource, $playerResource, $knockoutResource);
};

/**
 * @param $c ContainerInterface
 * @return BabyfootTournamentAction
 */
$container['App\Action\BabyfootTournamentAction'] = function ($c) {
    $playerResource = new PlayerResource($c->get('em'));
    $gameResource = new BabyfootGameResource($c->get('em'));
    $goalResource = new BabyfootGoalResource($c->get('em'));
    $teamResource = new BabyfootTeamResource($c->get('em'));
    $tournamentResource = new BabyfootTournamentResource($c->get('em'));
    $knockoutResource = new BabyfootGameKnockoutResource($c->get('em'));
    return new BabyfootTournamentAction($c->get('logger'), $teamResource, $gameResource, $goalResource,
        $playerResource, $tournamentResource, $knockoutResource);
};

/**
 * @param $c ContainerInterface
 * @return OrganizationAction
 */
$container['App\Action\OrganizationAction'] = function ($c) {
    $organizationResource = new OrganizationResource($c->get('em'));
    return new OrganizationAction($organizationResource);
};
