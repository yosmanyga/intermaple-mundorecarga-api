<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symsonte\Http\App;
use Symsonte\ServiceKit\PerpetualCachedContainer;

$app = new App(new PerpetualCachedContainer(
    sprintf('%s/../config/parameters.yml', __DIR__),
    [],
    sprintf('%s/../var/cache', __DIR__),
    ['Mundorecarga\\', 'Yosmy\\', 'Symsonte\\']
));
$app->execute(
    'mundorecarga.http.server.controller_dispatcher',
    sprintf('%s/../src', __DIR__)
);
