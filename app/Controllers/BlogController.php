<?php


namespace App\Controllers;

class BlogController
{


    public function index()
    {
        echo 'je suis la home page';
    }

    public function show(int $id)
    {
        echo 'je suis le post ' . $id;
    }


}


?>