<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return 'It works';
});

$app->put('/api/player', 'App\Action\PlayerAction:create');
$app->get('/api/players', 'App\Action\PlayerAction:fetch');
$app->get('/api/player/{player_id}', 'App\Action\PlayerAction:fetchOne');
