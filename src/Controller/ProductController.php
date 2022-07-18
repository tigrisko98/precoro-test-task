<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product')]
    public function index(ManagerRegistry $doctrine)
    {
        $products = $doctrine->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    #[Route('/product/create', name: 'app_product_create')]
    public function create(Request $request, ManagerRegistry $doctrine)
    {
        $product = new Product();

        $form = $this->createForm(CreateProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Product {$product->getName()} has been added."
            );

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/create.html.twig', [
            'controller_name' => 'ProductController',
            'create_product_form' => $form->createView()
        ]);

    }
}
