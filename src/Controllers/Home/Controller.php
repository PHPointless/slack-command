<?php

namespace Monolol\Slack\Controllers\Home;

use Spear\Silex\Application\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Spear\Silex\Provider\Traits\TwigAware;

class Controller
{
    use
        Traits\RequestAware,
        LoggerAwareTrait;

    private 
        $lolifiers;

    public function __construct(array $lolifiers)
    {
        $this->logger = new NullLogger();
        $this->lolifiers = $lolifiers;
    }

    public function lolifyAction()
    {
        $lolifier = $this->lolifiers[array_rand($this->lolifiers)];

        $text = $this->request->get('text');

        if(! empty($text))
        {
            $text = $lolifier->lolify(['message' => $text]);
        }

        return new Response($text['message']);
    }
}
