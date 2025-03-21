<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
final class AdminCategoryController extends AbstractController
{
    #[Route('', name: 'app_admin_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin_category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/show/{id}', name: 'app_admin_category.show', requirements: ['id' => '\d+'])]
    public function show(CategoryRepository $categoryRepository, int $id): Response
    {
        $categorie = $categoryRepository->find($id);
        return $this->render('admin_category/category_show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/add', name: 'app_admin_add_category')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Category();


        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin_category/add-category.html.twig', [
            'form' => $form->createView(),
        ]);
        // return $this->redirectToRoute('app_admin_category');
    }

    #[Route('/update/{id}', name: 'app_admin_update_category' /*, requirements: ['id' => '\d+']  Pas besoin car on précise le type de la variable dan sles paramètres de la fonction */)]
    // Si on lui donne une Category $category en paramètre à la place de l'id
    // Symfony comprendrendra qu'on veut l'instance de Category correspondant à l'id donné en url
    // Donc pas besoin de find et de CategoryRepository
    public function update(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, Request $request, /*int $id*/ Category $category): Response
    {
        // $category = $categoryRepository->find($id);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_category');
        }

        return $this->render('admin_category/update-category.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remove/{id}', name: 'app_admin_remove_category', requirements: ['id' => '\d+'])]
    public function remove(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $categoryRepository->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_category');
    }
}
