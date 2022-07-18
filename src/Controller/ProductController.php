<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(ManagerRegistry $doctrine)
    {
        $products = $doctrine->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    #[Route('/product/create', name: 'product_create')]
    public function create(Request $request, ManagerRegistry $doctrine)
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
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
            'create_product_form' => $form->createView()
        ]);

    }

    #[Route('/product/{id}/update', name: 'product_update')]
    public function update(Product $product, Request $request, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
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
            'create_product_form' => $form->createView()
        ]);

    }

    #[Route('/product/{id}/delete', name: 'product_delete')]
    public function delete(Product $product, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('products');
    }

}
