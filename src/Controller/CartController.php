<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $productsInCart = $this->getProducts();
        $products = [];

        if (!empty($productsInCart)) {
            $productsIds = array_keys($productsInCart);
            $products = $doctrine->getRepository(Product::class)->findBy(['id' => $productsIds]);
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'products' => $products,
            'productsInCart' => $productsInCart
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product, Request $request)
    {
        $session = $this->requestStack->getSession();
        $productsInCart = $this->getProducts();
        $quantity = 1;

        if ($request->get('quantity')) {
            $quantity = $request->get('quantity');
        }

        $productsInCart[$product->getId()] = $quantity;
        $session->set('products', $productsInCart);

        return $this->getQuantity();
    }

    #[Route('/cart/get-quantity', name: 'cart_get-quantity')]
    public function getQuantity()
    {
        $productsInCart = $this->getProducts();
        $productsInCartCount = 0;

        foreach ($productsInCart as $productId => $productQuantity) {
            $productsInCartCount += $productQuantity;
        }

        return new Response($productsInCartCount);
    }

    #[Route('/cart/is-product-in-cart/{id}', name: 'cart/is-product-in-cart')]
    public function isProductInCart($id)
    {
        if (array_key_exists($id, $this->getProducts())) {
            return new Response(true);
        }

        return new Response(false);
    }

    #[Route('/cart/delete/{id}', name: 'cart/delete')]
    public function deleteProduct($id)
    {
        $productsInCart = $this->getProducts();

        if (!empty($productsInCart) && in_array($id, array_keys($productsInCart))) {
            unset($productsInCart[$id]);
            $session = $this->requestStack->getSession();
            $session->set('products', $productsInCart);

            return new Response('Success');
        }

        return new Response('No such product in the cart');
    }

    private function getProducts()
    {
        $session = $this->requestStack->getSession();

        if ($session->get('products')) {
            return $session->get('products');
        }

        return [];
    }
}
