<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('calculateColor', [$this, 'calculateColor']),
        ];
    }

    public function calculateColor($seed) {
        srand(crc32($seed));
        return '#' . dechex(rand(0, 16777215));
    }
}