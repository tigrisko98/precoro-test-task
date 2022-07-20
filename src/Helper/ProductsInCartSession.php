<?php

namespace App\Helper;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

class ProductsInCartSession
{
    private $requestStack;

    private const SESSION_NAME = 'products';

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function get()
    {
        $session = $this->requestStack->getSession();

        if ($session->get(self::SESSION_NAME)) {
            return $session->get(self::SESSION_NAME);
        }

        return [];
    }

    public function set($products)
    {
        $session = $this->requestStack->getSession();

        return $session->set(self::SESSION_NAME, $products);
    }

    public function getTotalPrice(ManagerRegistry $doctrine)
    {
        $productsInCart = $this->get();
        $productsIds = array_keys($productsInCart);
        $products = $doctrine->getRepository(Product::class)->findBy(['id' => $productsIds]);
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product->getPrice() * $productsInCart[$product->getId()];
        }

        return $totalPrice;
    }
}
