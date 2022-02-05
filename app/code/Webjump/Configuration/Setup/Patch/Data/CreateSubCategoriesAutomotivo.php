<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Setup\Model\Bootstrap;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CreateSubCategoriesAutomotivo implements DataPatchInterface
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
        return[];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $categoryTitle = 'Automotivo';

        $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $categoryTitle)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        $subCategories = ['Volt 3', 'Volt SX', 'Roadmaster', 'Acessories'];

        foreach ($subCategories as $nome){
            $automotivoCategory = $this->categoryFactory->create();
            $automotivoCategory->isObjectNew(true);
            $automotivoCategory->setName($nome)
                ->setParentId($categoryId)
                ->setIsActive(true);
            $this->categoryRepository->save($automotivoCategory);
        }

        $this->moduleDataSetup->getConnection()->endSetup();

    }
}
