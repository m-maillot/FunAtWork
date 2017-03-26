<?php
// DIC configuration

use App\Action\BabyfootAction;
use App\Action\PlayerAction;
use App\Resource\Babyfoot\BabyfootGameResource;
use App\Resource\Babyfoot\BabyfootGoalResource;
use App\Resource\Babyfoot\BabyfootTeamResource;
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
    return new PlayerAction($playerResource);
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
    return new BabyfootAction($c->get('logger'), $teamResource, $gameResource, $goalResource, $playerResource);
};
