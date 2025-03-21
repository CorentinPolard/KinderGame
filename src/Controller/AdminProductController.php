<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdminProductController extends AbstractController
{

    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(ProductRepository $ProductRepository): Response
    {
        $products = $ProductRepository->findAll();
        return $this->render('admin_product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/admin/product/show/{id}', name: 'app_admin_product.show', requirements: ['id' => '\d+'])]
    public function show(/*ProductRepository $ProductRepository, int $id */Product $product): Response
    {
        // $product = $ProductRepository->find($id);
        return $this->render('admin_product/product_show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/admin/product/add', name: 'app_admin_add_product')]
    public function add(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/medias')] string $mediasDirectory): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaFile = $form->get('media')->getData();
            if ($mediaFile) {
                $originalFileName = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $mediaFile->guessExtension();
                try {
                    $mediaFile->move($mediasDirectory, $newFileName);
                } catch (FileException $e) {
                }

                $product->setMedia($newFileName);
            }


            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/add-product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/update/{id}', name: 'app_admin_update_product', requirements: ['id' => '\d+'])]
    public function update(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger, #[Autowire('%kernel.project_dir%/public/uploads/medias')] string $mediasDirectory, Product $product /*ProductRepository $ProductRepository, int $id*/): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaFile = $form->get('media')->getData();
            if ($mediaFile) {
                $originalFileName = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $mediaFile->guessExtension();
                try {
                    $mediaFile->move($mediasDirectory, $newFileName);
                } catch (FileException $e) {
                }

                $product->setMedia($newFileName);
            }


            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/update-product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/product/remove/{id}', name: 'app_admin_remove_product', requirements: ['id' => '\d+'])]
    public function remove(ProductRepository $ProductRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $ProductRepository->find($id);
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_product');
    }
}
