<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DroitsController extends AbstractController
{
    #[Route('/droits', name: 'app_droits')]
    public function index(): Response
    {
        return $this->render('droits/droits.html.twig', [
            'controller_name' => 'DroitsController',
        ]);
    }
}
