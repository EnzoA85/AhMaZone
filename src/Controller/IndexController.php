<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function afficheList(ManagerRegistry $doctrine): Response
    {
        $produitsRepository = $doctrine->getRepository(Produits::class);
        $produits = $produitsRepository->findAll();
        $listeProduit = [];
        foreach ($produits as $produit ){
            $listeProduit[] = array("id"=>$produit->getID(),"libelle" => $produit->getLibelle(),"description"=>$produit->getDescription(),"caracteristique"=>$produit->getCaracteristique(),"prix"=>$produit->getPrixUnitaireTTC(),"quantite"=>$produit->getQuatiteStock(),"img"=>$produit->getImg());
        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController','listeProduit' => $listeProduit,
        ]);
    }

    #[Route("/image/{id}", name:"show_image")]
    public function showImage(ManagerRegistry $doctrine, int $id): Response
    {
        $entity = $doctrine->getRepository(Produits::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('L\'entité n\'a pas été trouvée.');
        }

        $imageData = stream_get_contents($entity->getImg()); // Convertit la ressource en chaîne de caractères

        return new Response($imageData, 200, [
            'Content-Type' => 'image/png', // Assurez-vous d'ajuster le type MIME en fonction de votre image
        ]);
    }
}
