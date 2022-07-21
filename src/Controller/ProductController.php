<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductToOrder;
use App\Form\ProductFormType;
use App\Helper\ProductsInCartSession;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductToOrderRepository;

class ProductController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/products', name: 'products')]
    public function index()
    {
        $products = $this->doctrine->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Product {$product->getName()} has been added."
            );

            return $this->redirectToRoute('products');
        }

        return $this->render('product/create.html.twig', [
            'controller_name' => 'ProductController',
            'product_form' => $form->createView()
        ]);

    }

    #[Route('/product/{id}/update', name: 'product_update')]
    public function update(Product $product, Request $request)
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Product has been updated."
            );

            return $this->redirectToRoute('product_update', [
                'id' => $product->getId()
            ]);
        }

        return $this->render('product/update.html.twig', [
            'controller_name' => 'ProductController',
            'product_form' => $form->createView()
        ]);

    }

    #[Route('/product/{id}/delete', name: 'product_delete')]
    public function delete(Product $product)
    {
        $entityManager = $this->doctrine->getManager();
        $productsToOrder = $product->getProductToOrders();

        foreach ($productsToOrder as $productToOrder) {
            $entityManager->remove($productToOrder);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Success');
    }

    #[Route('/product/{id}', name: 'product_view')]
    public function view(Product $product)
    {
        return $this->render('product/view.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    }
}
