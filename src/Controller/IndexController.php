<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;

class IndexController extends AbstractController
{
    use ControllerTrait;
    #[Route('/', name: 'app_index')]
    public function afficheList(ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        $produitsRepository = $doctrine->getRepository(Produits::class);
        $produits = $produitsRepository->findAll();
        $listeProduit = [];
        $listeProduitAleatoire = [];

        foreach ($produits as $produit) {
            $listeProduit[] = array(
                "id" => $produit->getID(),
                "libelle" => $produit->getLibelle(),
                "description" => $produit->getDescription(),
                "caracteristique" => $produit->getCaracteristique(),
                "prix" => $produit->getPrixUnitaireTTC(),
                "quantite" => $produit->getQuatiteStock(),
                "img" => $produit->getImg(),
            );
        }

        // Obtenir 7 indices aléatoires du tableau $listeProduit
        $indicesAleatoires = array_rand($listeProduit, 9);

        // Remplir $listeProduitAleatoire avec les produits correspondants aux indices aléatoires
        foreach ($indicesAleatoires as $indice) {
            $listeProduitAleatoire[] = $listeProduit[$indice];
        }

        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('index/index.html.twig', [
            'listeProduit' => $listeProduit,
            'listeProduitAleatoire' => $listeProduitAleatoire, // Ajout de la variable pour la vue
            'quantiteTotale' => $quantiteTotale,
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
