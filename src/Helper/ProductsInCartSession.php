<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ProductRepository;

class ProductsInCartSession
{
    private RequestStack $requestStack;
    private ProductRepository $productRepository;

    private const SESSION_NAME = 'products';

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    public function get()
    {
        $session = $this->requestStack->getSession();

        if ($session->get(self::SESSION_NAME)) {
            return $session->get(self::SESSION_NAME);
        }

        return [];
    }

    public function set(array $products)
    {
        $session = $this->requestStack->getSession();

        return $session->set(self::SESSION_NAME, $products);
    }

    public function getTotalPrice(): int|float
    {
        $productsInCart = $this->get();
        $products = $this->productRepository->getProductsFromSessionById($productsInCart);
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->getPrice() * $productsInCart[$product->getId()];
        }

        return $totalPrice;
    }

    public function clear()
    {
        $session = $this->requestStack->getSession();

        return $session->remove(self::SESSION_NAME);
    }
}
