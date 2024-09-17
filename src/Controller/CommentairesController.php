<?php

namespace App\Controller;

use Doctrine\ORM\Mapping\Id;
use App\Entity\User;
use App\Entity\Commentaires;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentairesController extends AbstractController
{
    #[Route('/commentaires/delete/{id}', name: 'commentaires_delete')]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_home');
        }

        $commentaire = $doctrine->getRepository(Commentaires::class)->find($id);
        $entityManager = $doctrine->getManager();

        $entityManager->remove($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('commentaires_delete');
    }
}
