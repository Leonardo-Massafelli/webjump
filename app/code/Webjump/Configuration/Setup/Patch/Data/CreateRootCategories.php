<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Setup\Model\Bootstrap;

class CreateRootCategories implements DataPatchInterface
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
        CategoryRepositoryInterface $categoryRepository)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
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

        $automotiveCategory = $this->categoryFactory->create();
        $automotiveCategory->isObjectNew(true);
        $automotiveCategory->setName('Automotivo')
            ->setParentId(Category::TREE_ROOT_ID)
            ->setIsActive(true)
            ->setPosition(2);
        $this->categoryRepository->save($automotiveCategory);

        $festaCategory = $this->categoryFactory->create();
        $festaCategory->isObjectNew(true);
        $festaCategory->setName('Festa')
            ->setParentId(Category::TREE_ROOT_ID)
            ->setIsActive(true)
            ->setPosition(3);
        $this->categoryRepository->save($festaCategory);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
