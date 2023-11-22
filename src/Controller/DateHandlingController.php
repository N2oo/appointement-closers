<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DateHandlingController extends AbstractController
{
    #[Route('/make/suggestions', name: 'app_suggestion_maker',methods:["POST"])]
    public function index(): Response
    {
        return new Response("hello");
    }
}
