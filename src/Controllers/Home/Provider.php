<?php

namespace Monolol\Slack\Controllers\Home;

use Silex\ControllerProviderInterface;
use Silex\Application;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.home'] = function() use($app) {
            $controller = new Controller($app['lolifiers']);
            $controller
                ->setRequest($app['request']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/lolify', 'controller.home:lolifyAction')
            ->method('POST')
            ->bind('lolify');

        return $controllers;
    }
}
