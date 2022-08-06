<?php

namespace Router;

class Helpers
{

    public function pathToPublic($numberOfPaths)
    {
        $link = '';
        $link2 = $link;
        for ($i = 1; $i < $numberOfPaths; $i++) {
            $link2 = sprintf("%s%s", $link, '../');
            $link = $link2;
        }
        // $link = $link2;
        return $link;
    }

    public function isLogged()
    {
        
    }
}