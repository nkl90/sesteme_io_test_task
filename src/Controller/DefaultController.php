<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'show_form')]
    public function showForm()
    {
        return $this->render('default/form.html.twig');
    }

    #[Route('/process', name: 'process_form', methods: ['POST'])]
    public function processForm()
    {
        return new RedirectResponse('/');
    }
}