<?php

namespace App\Controller;

use App\Form\ChangeMailType;
use App\Form\ChangeNameType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    use ControllerTrait;
    #[Route('/profil/edit/{section}', name: 'app_profilEdit')]
    public function edit(SessionInterface $session, $section): Response
    {
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);

        return $this->render('account/profilEdit.html.twig', [
            'section' => $section,'quantiteTotale' => $quantiteTotale
        ]);
    }

    #[Route('/account/change-password',name:"account_change_password")]
    public function changePasswordForm(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);
        $form = $this->createForm(ChangePasswordType::class);
        return $this->render('account/_change_password_form.html.twig',['quantiteTotale' => $quantiteTotale,'form' => $form->createView()]);
    }

    #[Route('/account/change-email',name:"account_change_email")]
    public function changeEmailForm(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);
        $form = $this->createForm(ChangeMailType::class);
        return $this->render('account/_change_email_form.html.twig',['quantiteTotale' => $quantiteTotale,'form' => $form->createView()]);
    }

    #[Route('/account/change-name',name:"account_change_name")]
    public function changeNameForm(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $quantiteTotale = $this->getQuantiteTotale($session, $panier);
        $form = $this->createForm(ChangeNameType::class);
        return $this->render('account/_change_name_form.html.twig',['quantiteTotale' => $quantiteTotale,'form' => $form->createView()]);
    }
}
