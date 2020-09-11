<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasicController extends AbstractController
{
    /**
     * Display home page.
     *
     * @Route("/", methods={"GET"}, name="api_home")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
