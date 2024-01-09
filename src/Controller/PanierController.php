<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Produits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PanierController extends AbstractController
{
    use ControllerTrait;

    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);

        $produits = [];
        $totalPanier = 0;
        foreach($panier as $id => $quantite) {
            $produit = $entityManager->getRepository(Produits::class)->find($id);
            if($produit) {
                $produits[$id] = $produit;
                $totalPanier += $produit->getPrixUnitaireTTC() * $quantite; // assuming getPrice() returns the price of the product
            }
        }

        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'produits' => $produits,
            'quantiteTotale' => $quantiteTotale,
            'totalPanier' => $totalPanier,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'panier_ajouter')]
    public function ajouterProduit(Produits $produit, SessionInterface $session, Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantite = $request->request->get('quantite_' . $produit->getId());
        $panier = $session->get('panier', []);

        if (!empty($panier[$produit->getId()])) {
            $panier[$produit->getId()] += $quantite;
        } else {
            $panier[$produit->getId()] = $quantite;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_index');
    }




    #[Route('/panier/modifier', name: 'panier_modifier', methods: ['POST'])]
    public function modifierPanier(SessionInterface $session, Request $request): Response
    {
        $panier = $session->get('panier', []);

        foreach ($request->request->all() as $key => $value) {
            if (strpos($key, 'quantite_') === 0) {
                $produitId = substr($key, strlen('quantite_'));
                $quantite = max(1, (int)$value);

                // Mettre à jour la quantité dans le panier
                if (array_key_exists($produitId, $panier)) {
                    $panier[$produitId] = $quantite;
                }
            }
        }

        // Mettre à jour le panier dans la session
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/supprimer/{id}', name: 'panier_supprimer')]
    public function supprimerProduit(Produits $produit, SessionInterface $session): Response
    {
        $produitId = $produit->getId();

        // Supprimer le produit du panier
        $panier = $session->get('panier', []);
        if (array_key_exists($produitId, $panier)) {
            unset($panier[$produitId]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/vider', name: 'panier_vider')]
    public function viderPanier(SessionInterface $session): RedirectResponse
    {
        // Supprimer la clé 'panier' de la session pour vider le panier
        $session->remove('panier');

        // Redirigez l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_panier');
    }
}
