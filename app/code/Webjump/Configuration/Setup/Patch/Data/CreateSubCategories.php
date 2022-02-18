<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CreateSubCategories implements DataPatchInterface
{

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $collectionFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public static function getDependencies()
    {
        return[CreateRootCategories::class];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {

        //Creating subcategories for festa categories
        $categoryId = $this->getCategoryId('Datas comemorativas');

        $subcategorias = ['Páscoa', 'Halloween', 'Natal'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Festa temática');

        $subcategorias = ['Pool Party', 'Festa a Fantasia', 'Super-Heróis'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Festa Infantil');

        $subcategorias = ['Fantasias'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Balões e Bexigas');

        $subcategorias = ['Balões', 'Bexigas Simples', 'Bexigas Customizadas'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Decoração');

        $subcategorias = ['Topper', 'Painéis'];

        $this->createCategories($categoryId, $subcategorias);


        //creating subcategories for automotivo categories
        $categoryId = $this->getCategoryId('Volt 3');

        $subcategorias = ['Volt 3', 'Volt 3 Plaid'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Volt SX');

        $subcategorias = ['Volt SX', 'Volt SX Plaid'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Roadmaster');

        $subcategorias = ['Roadmaster', 'Roadmaster Plaid'];

        $this->createCategories($categoryId, $subcategorias);

        //
        $categoryId = $this->getCategoryId('Acessories');

        $subcategorias = ['Charging', 'Vehicle Acessories'];

        $this->createCategories($categoryId, $subcategorias);
    }

    private function getCategoryId($nome)
    {
        $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $nome)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        return $categoryId;
    }

    private function createCategories($categoryId, $categorias)
    {
        foreach ($categorias as $nome){
            $automotivoCategory = $this->categoryFactory->create();
            $automotivoCategory->setName($nome)
                ->setParentId($categoryId)
                ->setIsActive(true);
            $this->categoryRepository->save($automotivoCategory);
        }
    }

}
