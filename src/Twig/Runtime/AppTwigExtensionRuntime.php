<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppTwigExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
        // Inject dependencies if needed
    }

    public function formatPrice(int|float $price): string
    {
        return number_format($price, 2, ",") . " €";
    }

    public function getCategories(): array
    {
        return $this->categoryRepository->findAll();
    }
}
