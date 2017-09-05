<?php
// Routes

use App\Middleware\TokenAuth;

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return 'Nothing here :)';
});

$app->group('/api/v1', function () use ($app) {

    $this->map(['POST'], '/signin', "App\Action\PlayerAction:signin");

    $this->map(['POST'], '/groups', 'App\Action\GroupAction:create')->add(new TokenAuth($app, TokenAuth::SCOPE_ADMIN));
    $this->map(['GET'], '/groups', 'App\Action\GroupAction:fetch')->add(new TokenAuth($app, TokenAuth::SCOPE_ADMIN));
    $this->map(['GET'], '/groups/{group_id}', 'App\Action\GroupAction:fetchOne')->add(new TokenAuth($app, TokenAuth::SCOPE_ADMIN));
    // Player API
    $this->map(['POST'], '/players', 'App\Action\PlayerAction:create')->add(new TokenAuth($app, TokenAuth::SCOPE_ADMIN));
    $this->map(['GET'], '/players', 'App\Action\PlayerAction:fetch')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
    $this->map(['GET'], '/players/{player_id}', 'App\Action\PlayerAction:fetchOne')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));

    // Babyfoot API
    $this->group('/babyfoot', function () use ($app) {
        $this->map(['GET'], '/games', 'App\Action\BabyfootAction:fetchGames')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['POST'], '/start', 'App\Action\BabyfootAction:startGame')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['GET'], '/games/{game_id}', 'App\Action\BabyfootAction:fetchOneGame')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['POST'], '/stop', 'App\Action\BabyfootAction:gameOver')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['POST'], '/goal', 'App\Action\BabyfootAction:addGoal')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['GET'], '/stats/team', 'App\Action\BabyfootAction:computeTeamStats')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['GET'], '/stats/player', 'App\Action\BabyfootAction:computePlayerStats')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
        $this->map(['GET'], '/stats/player/{player_id}', 'App\Action\BabyfootAction:computeUniquePlayerStats')->add(new TokenAuth($app, TokenAuth::SCOPE_LOGGED));
    });
});