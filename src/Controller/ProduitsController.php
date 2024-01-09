<?php

namespace App\Controller;

use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    use ControllerTrait;

    #[Route('/produits', name: 'app_produits')]
    public function produits(ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $produitsRepository = $doctrine->getRepository(Produits::class);
        $produits = $produitsRepository->findAll();
        $listeProduit = [];
        foreach ($produits as $produit ){
            $listeProduit[] = array("id"=>$produit->getID(),"libelle" => $produit->getLibelle(),"description"=>$produit->getDescription(),"caracteristique"=>$produit->getCaracteristique(),"prix"=>$produit->getPrixUnitaireTTC(),"quantite"=>$produit->getQuatiteStock(),"img"=>$produit->getImg());
        }

        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('produits/produits.html.twig', [
            'listeProduit' => $listeProduit,
            'quantiteTotale' => $quantiteTotale,
        ]);
    }

    #[Route('/produits/{libelle}', name: 'app_produit')]
    public function produit($libelle, ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $produitsRepository = $doctrine->getRepository(Produits::class);
        $leProduit = $produitsRepository->findBy(array('libelle'=>$libelle));
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);
        return $this->render('produits/produit.html.twig', [
            'leProduit' => $leProduit,'quantiteTotale' => $quantiteTotale,
        ]);
    }

    #[Route("/search/{query}",name:'search_product')]
    public function search($query,ManagerRegistry $doctrine)
    {
        // Implémentez la logique de recherche ici, par exemple, récupérez le produit par son nom
        $product = $doctrine->getRepository(Produits::class)->findOneBy(['libelle' => $query]);

        if (!$product) {
            // Gérez le cas où le produit n'est pas trouvé (redirection, message d'erreur, etc.)
            // Vous pouvez rediriger vers la page d'accueil ou afficher une page d'erreur
            return $this->redirectToRoute('app_index');
        }

        // Redirigez vers la page du produit
        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }
}
