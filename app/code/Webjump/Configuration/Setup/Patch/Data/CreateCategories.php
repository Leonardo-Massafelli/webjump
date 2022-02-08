<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CreateCategories implements DataPatchInterface
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
        $this->moduleDataSetup->getConnection()->startSetup();

        //creating categories for Automotivo root category
        $categoryIdAutomotivo = $this->getCategoryId('Automotivo');

        $categoriesAutomotivo = ['Volt 3', 'Volt SX', 'Roadmaster', 'Acessories'];

        $this->createCategories($categoryIdAutomotivo, $categoriesAutomotivo);

        //creating categories for Festa root category
        $categoryIdFesta = $this->getCategoryId('Festa');

        $categoriesFesta = ['Datas comemorativas', 'Festa temática', 'Festa infantil', 'Balões e bexigas', 'Decoração'];

        $this->createCategories($categoryIdFesta, $categoriesFesta);

        $this->moduleDataSetup->getConnection()->endSetup();

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
