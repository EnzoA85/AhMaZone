<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    use ControllerTrait;
    #[Route('/profil', name: 'app_profil')]
    public function index(SessionInterface $session,Security $security): Response
    {
        $user = $security->getUser();
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController','quantiteTotale' => $quantiteTotale,'client'=>$user
        ]);
    }
}
