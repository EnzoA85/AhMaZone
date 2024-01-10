<?php

namespace App\Controller;

use App\Entity\Acheter;
use App\Entity\Commande;
use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    use ControllerTrait;
    #[Route('/profil', name: 'app_profil')]
    public function index(ManagerRegistry $doctrine,SessionInterface $session,Security $security): Response
    {
        $user = $security->getUser();
        $commandeRepository = $doctrine->getRepository(Commande::class);
        $listeCommande = [];
        $Commandes = $commandeRepository->findBy(['client' => $user->getid()]);
        foreach ($Commandes as $Commande ){
            $listeCommande[] = array("id"=>$Commande->getId(),"date" => $Commande->getDate(),"prixTotal"=>$Commande->getPrixTotalTTC());
        }
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController','quantiteTotale' => $quantiteTotale,'client'=>$user, 'listeCommande'=>$listeCommande
        ]);
    }

    #[Route('/profil/commande/{id}', name:'app_commande')]
    public function commande($id,ManagerRegistry $doctrine,SessionInterface $session) : Response
    {
        $commandeRepository = $doctrine->getRepository(Commande::class);
        $laCommande = $commandeRepository->find($id);
        
        $produitCommandeRepository = $doctrine->getRepository(Acheter::class);
        $produitsCommande = $produitCommandeRepository->findby(['commande'=>$laCommande->getid()]);
        $listeProduit = [];

        $produitRepository = $doctrine->getRepository(Produits::class);

        foreach ($produitsCommande as $produitCommande ){
            $produit = $produitRepository->findby(['id'=>$produitCommande->getProduit()->getid()]);
            $listeProduit[] = array("produit"=>$produit);
        }

        //dd($listeProduit['0']['produit']['0']->getid());
        
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);
        return $this->render('profil/commande.html.twig', [
            'controller_name' => 'ProfilController','quantiteTotale' => $quantiteTotale, 'laCommande'=>$laCommande,'listeProduit'=>$listeProduit
        ]);
    }
}
