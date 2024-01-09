<?php

namespace App\Controller;

use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    #[Route('/produits/{id}', name: 'app_produits')]
    public function produits($id, ManagerRegistry $doctrine): Response
    {
        $produitsRepository = $doctrine->getRepository(Produits::class);
        $leProduit = $produitsRepository->find($id);
        return $this->render('produits/produit.html.twig', [
            'leProduit' => $leProduit,
        ]);
    }
}
