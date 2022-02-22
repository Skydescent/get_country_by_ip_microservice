<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return new Response('First Page');
    }

    /**
     * @Route("/one_more_page")
     */
    public function oneMorePage()
    {
        return new Response('This is one more page');
    }
}
