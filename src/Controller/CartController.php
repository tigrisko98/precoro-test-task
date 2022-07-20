<?php

namespace App\Controller;

use App\Helper\ProductsInCartSession;
use phpDocumentor\Reflection\Types\Self_;
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
    public function index(ManagerRegistry $doctrine, ProductsInCartSession $productsInCartSession): Response
    {
        $productsInCart = $productsInCartSession->get();
        $products = [];
        $totalPrice = 0;

        if (!empty($productsInCart)) {
            $productsIds = array_keys($productsInCart);
            $products = $doctrine->getRepository(Product::class)->findBy(['id' => $productsIds]);
            $totalPrice = $this->getTotalPrice($doctrine, $productsInCartSession);
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'products' => $products,
            'products_in_cart' => $productsInCart,
            'total_price' => $totalPrice
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product, Request $request, ProductsInCartSession $productsInCartSession)
    {
        $productsInCart = $productsInCartSession->get();
        $quantity = 1;

        if ($request->get('quantity')) {
            $quantity = $request->get('quantity');
        }

        $productsInCart[$product->getId()] = $quantity;
        $productsInCartSession->set($productsInCart);

        return $this->getQuantity();
    }

    #[Route('/cart/get-quantity', name: 'cart_get-quantity')]
    public function getQuantity()
    {
        $productsInCart = (new ProductsInCartSession($this->requestStack))->get();
        $productsInCartCount = 0;

        foreach ($productsInCart as $productId => $productQuantity) {
            $productsInCartCount += $productQuantity;
        }

        return new Response($productsInCartCount);
    }

    #[Route('/cart/is-product-in-cart/{id}', name: 'cart/is-product-in-cart')]
    public function isProductInCart($id, ProductsInCartSession $productsInCartSession)
    {
        if (array_key_exists($id, $productsInCartSession->get())) {
            return new Response(true);
        }

        return new Response(false);
    }

    #[Route('/cart/delete/{id}', name: 'cart/delete')]
    public function deleteProduct($id)
    {
        $productsInCartSession = new ProductsInCartSession($this->requestStack);
        $productsInCart = $productsInCartSession->get();

        if (!empty($productsInCart) && in_array($id, array_keys($productsInCart))) {
            unset($productsInCart[$id]);
            $productsInCartSession->set($productsInCart);

            return new Response('Success');
        }

        return new Response('No such product in the cart');
    }

    #[Route('/cart/get-total-price', name: 'cart/get-total-price')]
    public function getTotalPrice(ManagerRegistry $doctrine, ProductsInCartSession $productsInCartSession)
    {
        return $productsInCartSession->getTotalPrice($doctrine);

    }
}
