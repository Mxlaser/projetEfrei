<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilsController extends AbstractController
{
    #[Route('/profils/admin', name: 'profils_admin')]
    public function admin(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('profils/profils-admin.html.twig', [
            'user' => $users,
        ]);
    }

    #[Route('/profils/guest', name: 'profils_guest')]
    public function guest(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profils/profils-guest.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profils/delete/{id}', name: 'profils_delete')]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }

        $user = $doctrine->getRepository(User::class)->find($id);
        $entityManager = $doctrine->getManager();

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('profils_admin');
    }
}
