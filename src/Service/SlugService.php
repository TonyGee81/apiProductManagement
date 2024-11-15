<?php

namespace App\Service;

use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugService
{
    public function slugify(string $string): string
    {
        $slugger = new AsciiSlugger();

        return $slugger->slug(strtolower($string));
    }
}
