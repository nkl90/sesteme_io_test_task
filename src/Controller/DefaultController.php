<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Operation;
use App\Form\CheckoutFormType;
use App\Form\OrderFormType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_default_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'orderform')]
    public function orderForm(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(OrderFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump(__METHOD__);
            /** @var Operation $data */
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('app_default_confirm', ['uuid' => $data->getId()]);
        }

        return $this->render(
            'default/order_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(
        '/confirm-payment/{uuid}',
        name: 'confirm',
        methods: ['GET', 'POST']
    )]
    public function confirmOrder(
        string $uuid,
        Request $request,
        OperationRepository $operationRepository,
    ): Response {
        $orderId = $request->get('uuid');
        $operation = $operationRepository->find($orderId);
        if (!$operation) {
            throw $this->createNotFoundException('Order not found');
        }
        $form = $this->createForm(CheckoutFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_default_congratulations');
        }

        return $this->render('default/checkout_form.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/congratulations', name: 'congratulations')]
    public function congratulations(): Response
    {
        return $this->render('default/congratulations.html.twig');
    }
}
