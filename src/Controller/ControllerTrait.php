<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait ControllerTrait
{
    private function getQuantiteTotale(SessionInterface $session, array $panier): int
    {
        $panier = $session->get('panier', []);
        $quantiteTotale = 0;

        foreach ($panier as $id => $quantite) {
            $quantiteTotale += $quantite;
        }

        return $quantiteTotale;
    }
}