<?php

namespace App\Controller;


use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/shop')]
final class ShopController extends AbstractController
{
    #[Route('', name: 'shop_index')]
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    // #[Route('/products', name: 'shop_products')]
    // public function liste(ProductRepository $productRepository): Response
    // {

    //     $products = $productRepository->findAll();

    //     if ($products != []) {
    //         return $this->render('shop/products.html.twig', [
    //             'products' => $products,
    //         ]);
    //     } else {
    //         return $this->render('shop/notExist.html.twig');
    //     }
    // }

    #[Route('/products', name: 'shop_products')]
    public function liste(ProductRepository $productRepository, Request $request): Response
    {
        // la variable limit est le nombre de produits par page que l'on veut
        $limit = 3;
        // Notre variable page est égale à la valeur du paramètre 'page' dans l'URL
        $page = $request->query->getInt('page', 1);

        if ($page < 1) {
            $page = 1;
        }

        $products = $productRepository->paginateProducts($page, $limit);

        // compte le nombre de produit dans la bdd 
        // le divise par $limit 
        // l'arrondit à l'entier supérieur pour déterminer le nombre max de pages
        // Exemple: Si on a 5 produits et qu'on le divise par $limit qui vaut 3, on obtient 1,6667. On peut pas avoir 1,6667 pages. 
        $maxPages = ceil($products->count() / $limit);

        if ($products != []) {
            return $this->render('shop/products.html.twig', [
                'products' => $products,
                'maxPages' => $maxPages,
                'page' => $page,
            ]);
        } else {
            return $this->render('shop/notExist.html.twig');
        }
    }

    #[Route('/show/{item}', name: 'shop_item')]
    public function item(string $item, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(['label' => $item]);

        if ($product != null) {
            $similars = $productRepository->findThreeByCategory($product->getCategory(), $product->getLabel());

            return $this->render('shop/item.html.twig', [
                'product' => $product,
                'similars' => $similars,
            ]);
        } else {
            return $this->render('shop/notExist.html.twig');
        }
    }

    #[Route('/category/{category_id}', name: 'shop_category')]
    public function category(int $category_id, ProductRepository $productRepository): Response
    {

        $productsByCategory = $productRepository->findBy(['category' => $category_id]);

        return $this->render('shop/category.html.twig', [
            'products' => $productsByCategory,
        ]);
    }
}
