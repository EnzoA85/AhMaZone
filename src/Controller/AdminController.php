<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    use ControllerTrait;
    #[Route('/admin', name: 'app_admin')]
    public function index(ManagerRegistry $doctrine,SessionInterface $session): Response
    {
        $clientRepository = $doctrine->getRepository(Client::class);
        $Clients = $clientRepository->findAll();

        $listeClient = [];
        foreach ($Clients as $Client ){
            $listeClient[] = array("id"=>$Client->getID(),"email" => $Client->getemail(),"nom"=>$Client->getnom(),"prenom"=>$Client->getprenom(),"adresse"=>$Client->getadresse(),"CP"=>$Client->getcodePostal(),"ville"=>$Client->getville());
        }

        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController','quantiteTotale' => $quantiteTotale,'listeClient'=>$listeClient
        ]);
    }
}
