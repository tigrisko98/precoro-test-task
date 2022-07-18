<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class CartController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product)
    {
        $session = $this->requestStack->getSession();
        $productsInCart = [];

        if ($session->get('products')) {
            $productsInCart = $session->get('products');
        }

        array_key_exists($product->getId(), $productsInCart)
            ? $productsInCart[$product->getId()]++
            : $productsInCart[$product->getId()] = 1;

        $session->set('products', $productsInCart);

        return new Response(true);
    }

    #[Route('/cart/get-quantity', name: 'cart_get-quantity')]
    public function getQuantity()
    {
        $session = $this->requestStack->getSession();

        if ($session->get('products')) {
            $productsInCartCount = 0;
            foreach ($session->get('products') as $productId => $productQuantity) {
                $productsInCartCount += $productQuantity;
            }
            return new Response($productsInCartCount);
        }

        return new Response(0);
    }
}
