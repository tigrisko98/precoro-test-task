<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductToOrder;
use App\Entity\User;
use App\Form\OrderFormType;
use App\Helper\ProductsInCartSession;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/order/create', name: 'order_create')]
    public function create(ManagerRegistry $doctrine, Request $request, ProductsInCartSession $productsInCartSession): Response
    {
        $productsInCart = $productsInCartSession->get();
        $productsIds = array_keys($productsInCart);
        $products = $doctrine->getRepository(Product::class)->findBy(['id' => $productsIds]);

        $user = new User();
        $order = new Order();

        $form = $this->createForm(OrderFormType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $user->setName($request->get('order_form')['user']);
            $entityManager->persist($user);
            $entityManager->flush();

            $order->setUser($user->getId());
            $entityManager->persist($order);
            $entityManager->flush();

            foreach ($products as $product) {
                $productToOrder = new ProductToOrder();
                $productToOrder->setProduct($product);
                $productToOrder->setOrderId($order->getId());
                $productToOrder->setQuantity($productsInCart[$product->getId()]);
                $productToOrder->setProductPrice($product->getPrice());
                $order->addProductToOrder($productToOrder);
                $entityManager->persist($productToOrder);
            }

            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                "Order has been added."
            );

            return $this->redirectToRoute('products');
        }

        return $this->render('order/create.html.twig', [
            'controller_name' => 'OrderController',
            'order_form' => $form->createView()
        ]);
    }
}
