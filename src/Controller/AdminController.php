<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Attribute\IsGranted;

#[Route('/shop')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    // #[IsGranted('ROLE_ADMIN')]   // Bonne syntaxe mais ne fonctionne pas pour moi
    public function index(): Response
    {
        // dd($this->isGranted(''));
        // if ($this->isGranted('ROLE_ADMIN')) {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
        // } else {
        //     return $this->render('shop/notExist.html.twig');
        // }
    }
}
