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

class CreateSubCategoriesFesta implements DataPatchInterface
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

        $categoryTitle = 'Festa';

        $collection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name', $categoryTitle)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        $subCategories = ['Datas comemorativas', 'Festa temática', 'Festa infantil', 'Balões e bexigas', 'Decoração'];

        foreach ($subCategories as $nome){
            $festaCategory = $this->categoryFactory->create();
            $festaCategory->isObjectNew(true);
            $festaCategory->setName($nome)
                ->setParentId($categoryId)
                ->setIsActive(true);
            $this->categoryRepository->save($festaCategory);
        }

        $this->moduleDataSetup->getConnection()->endSetup();

    }
}
