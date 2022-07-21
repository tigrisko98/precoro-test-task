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
use App\Repository\ProductRepository;

class OrderController extends AbstractController
{
    private ProductRepository $productRepository;
    private ManagerRegistry $doctrine;
    private ProductsInCartSession $productsSessionHelper;

    public function __construct(ProductRepository $productRepository, ManagerRegistry $doctrine, ProductsInCartSession $productsSessionHelper)
    {
        $this->productRepository = $productRepository;
        $this->doctrine = $doctrine;
        $this->productsSessionHelper = $productsSessionHelper;
    }

    #[Route('/orders', name: 'orders')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->find(61);
        dd($order);
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/order/create', name: 'order_create')]
    public function create(Request $request): Response
    {
        $productsInCart = $this->productsSessionHelper->get();

        if (empty($productsInCart)) {
            return new Response('There are no products in cart.');
        }

        $products = $this->productRepository->getProductsFromSessionById($productsInCart);
        $totalPrice = $this->productsSessionHelper->getTotalPrice();

        $user = new User();
        $order = new Order();

        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->doctrine->getManager();
            $user->setName($request->get('order_form')['user']);
            $entityManager->persist($user);
            $entityManager->flush($user);

            $order->setUser($user->getId());
            $entityManager->persist($order);

            foreach ($products as $product) {
                $productToOrder = new ProductToOrder();
                $productToOrder->setProduct($product);
                $productToOrder->setOrder($order);
                $productToOrder->setQuantity($productsInCart[$product->getId()]);
                $productToOrder->setProductPrice($product->getPrice());
                $entityManager->persist($productToOrder);
                $order->addProductToOrder($productToOrder);
            }

            $entityManager->flush();
            $this->productsSessionHelper->clear();

            $this->addFlash(
                'notice',
                "Order has been created."
            );

            return $this->redirectToRoute('products');
        }

        return $this->render('order/create.html.twig', [
            'controller_name' => 'OrderController',
            'total_price' => $totalPrice,
            'order_form' => $form->createView()
        ]);
    }
}
