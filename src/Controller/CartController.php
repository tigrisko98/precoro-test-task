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
    private ManagerRegistry $doctrine;
    private ProductsInCartSession $productsSessionHelper;

    public function __construct(ManagerRegistry $doctrine, ProductsInCartSession $productsSessionHelper)
    {
        $this->doctrine = $doctrine;
        $this->productsSessionHelper = $productsSessionHelper;
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $productsInCart = $this->productsSessionHelper->get();
        $products = [];
        $totalPrice = 0;

        if (!empty($productsInCart)) {
            $productsIds = array_keys($productsInCart);
            $products = $this->doctrine->getRepository(Product::class)->findBy(['id' => $productsIds]);
            $totalPrice = $this->getTotalPrice();
        }

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'products' => $products,
            'products_in_cart' => $productsInCart,
            'total_price' => $totalPrice
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product, Request $request)
    {
        $productsInCart = $this->productsSessionHelper->get();
        $quantity = 1;

        if ($request->get('quantity')) {
            $quantity = $request->get('quantity');
        }

        $productsInCart[$product->getId()] = $quantity;
        $this->productsSessionHelper->set($productsInCart);

        return $this->getQuantity();
    }

    #[Route('/cart/get-quantity', name: 'cart_get-quantity')]
    public function getQuantity()
    {
        $productsInCart = $this->productsSessionHelper->get();
        $productsInCartCount = 0;

        foreach ($productsInCart as $productId => $productQuantity) {
            $productsInCartCount += $productQuantity;
        }

        return new Response($productsInCartCount);
    }

    #[Route('/cart/is-product-in-cart/{id}', name: 'cart/is-product-in-cart')]
    public function isProductInCart($id)
    {
        if (array_key_exists($id, $this->productsSessionHelper->get())) {
            return new Response(true);
        }

        return new Response(false);
    }

    #[Route('/cart/delete/{id}', name: 'cart/delete')]
    public function deleteProduct($id)
    {
        $productsInCart = $this->productsSessionHelper->get();

        if (!empty($productsInCart) && in_array($id, array_keys($productsInCart))) {
            unset($productsInCart[$id]);
            $this->productsSessionHelper->set($productsInCart);

            return new Response('Success');
        }

        return new Response('No such product in the cart');
    }

    #[Route('/cart/get-total-price', name: 'cart/get-total-price')]
    public function getTotalPrice()
    {
        return $this->productsSessionHelper->getTotalPrice($this->doctrine);

    }

    #[Route('/cart/clear', name: 'cart/clear')]
    public function clear()
    {
        $this->productsSessionHelper->clear();

        return $this->redirectToRoute('cart');

    }
}
