<?php

namespace Router;

class PathToPublic
{

    public function link($nombreDeMots)
    {
        $link = '';
        $link2 = $link;
        for ($i = 1; $i < $nombreDeMots; $i++) {
            $link2 = sprintf("%s%s", $link, '../');
            $link = $link2;
        }
        // $link = $link2;
        return $link;
    }
}