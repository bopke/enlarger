<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RootController
{
    /**
     * @Route("/")
     */
    public function root(): Response
    {
        // TODO: landing page!
        return new Response('<html><body><h1>Hello.</h1></body></html>');
    }
}