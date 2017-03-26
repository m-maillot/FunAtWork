<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return 'Nothing here :)';
});

$app->group('/api/v1', function () {

    // Player API
    $this->map(['POST'], '/players', 'App\Action\PlayerAction:create');
    $this->map(['GET'], '/players', 'App\Action\PlayerAction:fetch');
    $this->map(['GET'], '/players/{player_id}', 'App\Action\PlayerAction:fetchOne');

    // Babyfoot API
    $this->group('/babyfoot', function () {
        $this->map(['GET'], '/games', 'App\Action\BabyfootAction:fetchGames');
        $this->map(['POST'], '/start', 'App\Action\BabyfootAction:startGame');
        $this->map(['POST'], '/stop', 'App\Action\BabyfootAction:gameOver');
        $this->map(['POST'], '/goal', 'App\Action\BabyfootAction:addGoal');
    });
});