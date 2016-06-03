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
        $text = $this->request->get('text');
        
        list($text, $lolifier) = $this->extractLolifier($text);

        if(! empty($text))
        {
            $textLolified = $lolifier->lolify(['message' => $text]);
            
            $text = $textLolified['message'];
        }

        return new Response($text);
    }
    
    private function extractLolifier($text)
    {
        $lolifier = $this->lolifiers[array_rand($this->lolifiers)];

        $lolifierName = $this->extractLolifierName($text);
        if(null !== $lolifierName)
        {
            if(array_key_exists(strtolower($lolifierName), $this->lolifiers))
            {
                $text = $this->cleanTextFromLolifierName($text, $lolifierName);

                $lolifier = $this->lolifiers[$lolifierName];
            }
        }

        return [
            $text,
            $lolifier,
        ];
    }
    
    private function cleanTextFromLolifierName($text, $lolifierName)
    {
        return preg_replace("|^$lolifierName\s{1,}(.*)|i", '${1}', $text);
    }

    private function extractLolifierName($text)
    {
        $lolifierName = null;
        $words = preg_split('~\s~', $text);

        if(count($words) > 1)
        {
            $lolifierName = $words[0];
        }
        
        return $lolifierName;
    }
}
