<?php

namespace Monolol\Slack;

use Spear\Silex\Application\AbstractApplication;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Spear\Silex\Provider;
use Puzzle\Configuration;
use Silex\Provider\UrlGeneratorServiceProvider;
use Monolol\Lolifiers;

class Application extends AbstractApplication
{
    protected function registerProviders()
    {
    }

    protected function initializeServices()
    {
        $this['lolifiers'] = $this->share(function() {
            $swearWordsProvider = new Lolifiers\SwearWordsProviders\DefaultProvider();

            return [
                'tourette' => new Lolifiers\Tourette($swearWordsProvider),
            ];
        });
    }

    protected function mountControllerProviders()
    {
        $this->mount('/', new Controllers\Home\Provider());
    }
}
