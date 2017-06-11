<?php
// Application middleware
$app->add(new \App\Middleware\TokenAuth(new \App\Resource\PlayerResource($app->getContainer()->get('em'))));
// e.g: $app->add(new \Slim\Csrf\Guard);
