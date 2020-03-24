<?php

namespace App\Controller;

use App\Entity\OrdersDetail;
use App\Form\OrdersDetailType;
use App\Repository\OrdersDetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders/detail")
 */
class OrdersDetailController extends AbstractController
{
    /**
     * @Route("/", name="orders_detail_index", methods={"GET"})
     */
    public function index(OrdersDetailRepository $ordersDetailRepository): Response
    {
        return $this->render('orders_detail/index.html.twig', [
            'orders_details' => $ordersDetailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="orders_detail_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ordersDetail = new OrdersDetail();
        $form = $this->createForm(OrdersDetailType::class, $ordersDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ordersDetail);
            $entityManager->flush();

            return $this->redirectToRoute('orders_detail_index');
        }

        return $this->render('orders_detail/new.html.twig', [
            'orders_detail' => $ordersDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_detail_show", methods={"GET"})
     */
    public function show(OrdersDetail $ordersDetail): Response
    {
        return $this->render('orders_detail/show.html.twig', [
            'orders_detail' => $ordersDetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orders_detail_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OrdersDetail $ordersDetail): Response
    {
        $form = $this->createForm(OrdersDetailType::class, $ordersDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_detail_index', [
                'id' => $ordersDetail->getId(),
            ]);
        }

        return $this->render('orders_detail/edit.html.twig', [
            'orders_detail' => $ordersDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_detail_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OrdersDetail $ordersDetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordersDetail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ordersDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('orders_detail_index');
    }
}
