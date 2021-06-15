<?php

declare(strict_types=1);

namespace App\Command;

use App\Form\CreateCategoryType;
use App\Form\ProductType;
use App\Form\Utils\FormErrorParser;
use App\Service\CategoryService;
use App\Service\ProductService;
use pcrov\JsonReader\JsonReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\FormFactoryInterface;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import';
    protected static $defaultDescription = 'Import products.';

    private const ALLOW_KEYS = [
        'eId',
        'title',
        'price',
        'categoriesEId'
    ];

    private CategoryService $categoryService;

    private ProductService $productService;

    private FormFactoryInterface $formFactory;

    private JsonReader $reader;

    public function __construct(
        CategoryService $categoryService,
        ProductService $productService,
        FormFactoryInterface $formFactory
    ) {
        $this->categoryService = $categoryService;
        $this->productService  = $productService;
        $this->formFactory     = $formFactory;
        $this->reader          = new JsonReader();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('product', null, InputOption::VALUE_OPTIONAL, 'Product Source File Path')
            ->addOption('category', null, InputOption::VALUE_OPTIONAL, 'Category Source File Path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io                 = new SymfonyStyle($input, $output);
        $productSourcePath  = $input->getOption('product');
        $categorySourcePath = $input->getOption('category');

        if ($productSourcePath) {
            if (!is_file($productSourcePath)) {
                $io->error("File worng: $productSourcePath");
                return Command::FAILURE;
            }
            $io->note(sprintf('You passed an argument: %s', $productSourcePath));
        } else {
            $productSourcePath = "src/DataFixtures/data/products.json";
            $io->note(sprintf('Import product form default file path: %s', $productSourcePath));
        }
        if ($categorySourcePath) {
            if (!is_file($categorySourcePath)) {
                $io->error("File worng: $categorySourcePath");
                return Command::FAILURE;
            }
            $io->note(sprintf('You passed an argument: %s', $productSourcePath));
        } else {
            $categorySourcePath = "src/DataFixtures/data/categories.json";
            $io->note(sprintf('Import product form default file path: %s', $categorySourcePath));
        }
        $io->note('Begin import');


        $categoryImportStatus = $this->importCategories($categorySourcePath);
        $io->success(json_encode($categoryImportStatus));

        $productImportStatus = $this->importProducts($productSourcePath);
        $io->success(json_encode($productImportStatus));

        $io->note('Import finish');
        return Command::SUCCESS;
    }

    private function importCategories(string $categorySourcePath): array
    {
        $this->reader->open($categorySourcePath);

        $this->reader->read();
        $depth = $this->reader->depth();

        $this->reader->read();
        $total    = 0;
        $success  = 0;
        $error    = 0;
        $errorMsg = [];

        do {
            $total++;

            $item = $this->reader->value();
            if (isset($item['eId'])) {
                $item['eid'] = $item['eId'];
                unset($item['eId']);
            }

            $form = $this->formFactory->createBuilder(CreateCategoryType::class)->getForm();

            $form->submit($item);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $category = $form->getData();
                    try {
                        $this->categoryService->createOne($category);
                        $success++;
                    } catch (\Exception $exception) {
                        $error++;
                        $errorMsg[] = $exception->getMessage();
                        continue;
                    }
                } else {
                    $error++;
                    $errors     = FormErrorParser::arrayParse($form);
                    $errorMsg[] = $errors;
                    continue;
                }
            }
        } while ($this->reader->next() && $this->reader->depth() > $depth);

        $this->reader->close();

        return [
            'total'    => $total,
            'success'  => $success,
            'error'    => $error,
            'errorMsg' => $errorMsg
        ];
    }

    private function importProducts(string $productSourcePath): array
    {
        $this->reader->open($productSourcePath);
        $this->reader->read();
        $depth = $this->reader->depth();

        $this->reader->read();
        $total    = 0;
        $success  = 0;
        $error    = 0;
        $errorMsg = [];

        do {
            $total++;

            $item = $this->reader->value();
            $form = $this->formFactory->createBuilder(ProductType::class)->getForm();

            if (isset($item['categoriesEId'])) {
                $item['categories'] = $item['categoriesEId'];
                unset($item['categoriesEId']);
            }
            if (isset($item['eId'])) {
                $item['eid'] = $item['eId'];
                unset($item['eId']);
            }
            $form->submit($item);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $category = $form->getData();
                    try {
                        $this->productService->createOne($category);
                        $success++;
                    } catch (\Exception $exception) {
                        $error++;
                        $errorMsg[] = $exception->getMessage();
                        continue;
                    }
                } else {
                    $error++;
                    $errors      = FormErrorParser::arrayParse($form);
                    $wrongFields = array_diff(array_keys($this->reader->value()), self::ALLOW_KEYS);
                    if (count($wrongFields) > 0 && isset($errors['product'])) {
                        $errors['product'] = [
                            "Fields not accepted: ".implode(", ", $wrongFields)
                        ];
                    }

                    $errorMsg[] = $errors;
                    continue;
                }
            }
        } while ($this->reader->next() && $this->reader->depth() > $depth);

        $this->reader->close();

        return [
            'total'    => $total,
            'success'  => $success,
            'error'    => $error,
            'errorMsg' => $errorMsg
        ];
    }
}
