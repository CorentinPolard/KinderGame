<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:stat',
    description: 'Donne les statistiques du site (nombres produits, catégories, users)...',
)]
class StatCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description', 'les gens !!')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    // protected function execute(InputInterface $input, OutputInterface $output): int
    // {
    //     $io = new SymfonyStyle($input, $output);
    //     $arg1 = $input->getArgument('arg1');

    //     if ($arg1) {
    //         $io->note(sprintf('You passed an argument: %s', $arg1));
    //     }

    //     if ($input->getOption('option1')) {
    //         // ...
    //     }

    //     $io->success('You have a new command! Now make it your own! Pass --help to see your options.');


    //     return Command::SUCCESS;
    // }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $usersNumber = count($this->userRepository->findAll());
        $productsNumber = count($this->productRepository->findAll());
        $categories = $this->categoryRepository->findAll();

        $categoriesName = [];
        foreach ($categories as $category) {
            $categoriesName[] = $category->getLabel();
        }

        $avgPrice = $this->productRepository->avgPrice();

        $mostExpensiveProducts = $this->productRepository->findMostExpensive();
        $maxPriceMessage = [];
        foreach ($mostExpensiveProducts as $product) {
            $maxPriceMessage[] = $product->getLabel() . " : " . number_format($product->getPrice(), 2, ',') . " €";
        }

        $lessExpensiveProducts = $this->productRepository->findLessExpensive();
        $minPriceMessage = [];
        foreach ($lessExpensiveProducts as $product) {
            $minPriceMessage[] = $product->getLabel() . " : " . number_format($product->getPrice(), 2, ',') . " €";
        }

        $io = new SymfonyStyle($input, $output);

        $io->title("Voici les statistiques de KinderGame");

        $io->section("Nombres d'utilisateurs et de produits en base de données :");
        $io->text([
            "Vous avez $usersNumber utilisateurs en base de données.",
            "Vous avez $productsNumber produits en base de données.",
        ]);

        $io->section("Liste des catégories :");
        $io->listing(
            $categoriesName,
        );

        $io->section("Moyenne des prix :");
        $io->text(
            "La moyenne des prix de vos produits vaut " . number_format($avgPrice, 2, ',') . " €."
        );

        $io->section("Produit(s) le(s) plus cher(s) :");
        $io->listing(
            $maxPriceMessage
        );

        $io->section("Produit(s) le(s) moins cher(s) :");
        $io->listing(
            $minPriceMessage
        );

        return Command::SUCCESS;
    }
}
