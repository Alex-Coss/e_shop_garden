<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/purchase", name="app_purchase_")
 * @IsGranted("ROLE_USER", message="Vous devez être connectés pour accéder à vos commandes !")
 */
class PurchasesListController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        // Connectée ou redirect login
        /** @var User */
        $user = $this->getUser(); // Récupère l'info USER du composant SECURITY


        // Redirect user à twig
        return $this->render('purchase/index.html.twig', 
        [
            'purchases' => $user->getPurchases()
        ]);
    }
}
