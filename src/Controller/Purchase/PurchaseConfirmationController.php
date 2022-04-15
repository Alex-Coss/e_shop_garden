<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Purchase\PurchaseResponsibility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/purchase", name="app_purchase_")
 */
class PurchaseConfirmationController extends AbstractController
{

    protected $cartService;
    protected $em;
    protected $responsibility;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchaseResponsibility $responsibility)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->responsibility = $responsibility;
    }

    /**
     * @Route("/confirmation", name="confirmation")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande !")
     */
    public function confirmation(Request $request): Response
    {
        // Récupération des données du formulaire
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted())
        {
            // Message flash d'aide
            $this->addFlash('warning', 'vous devez remplir le formulaire les amis !');
            // ! sécurité anti-require (front)
            // Retour à la bonne page, AVEC l'obligation de remplir le formulaire
            return $this->redirectToRoute('cart_browse');
        }

        // Vérif si panier vide
        $cartItem = $this->cartService->getDetailItems();

        if (count($cartItem) === 0)
        {
            $this->addFlash('warning', 'Le panier est vide ! ACHÈTE !');
            return $this->redirectToRoute('cart_browse');
        }

        // Création de la purchase
        /**
         * @var Purchase
         */
        $purchase = $form->getData();
        // dd($purchase);

        // Récupération du service responsibility, créé pour décharger du Controller de fonctions qui ne lui incombent pas.
        $this->responsibility->storePurchase($purchase);

        $this->cartService->empty();

        $this->addFlash('success', 'la commande a bien été redirigée');
        return $this->redirectToRoute('app_purchase_list');
    }
}
