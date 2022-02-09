<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\ResourceModel\Group;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CreateRootCategories implements DataPatchInterface
{
    private $moduleDataSetup;
    private $categoryFactory;
    private $categoryRepository;
    private $groupFactory;
    private $groupResourceModel;
    private $collectionFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository,
        GroupFactory $groupFactory,
        Group $groupResourceModel,
        CollectionFactory $collectionFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
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

        //creating automotivo root category
        $this->createCategories('Automotivo');
        $automotivoId = $this->getCategoryId('Automotivo');

        //creating festa root category
        $this->createCategories('Festa');
        $festaId = $this->getCategoryId('Festa');

        //setting automotivo root category to its store group
        $automotivo = $this->groupFactory->create();
        $this->groupResourceModel->load($automotivo, CreateWebsites::AUTOMOTIVO_GROUP_CODE, 'code');
        $automotivo->setRootCategoryId($automotivoId);
        $this->groupResourceModel->save($automotivo);

        //setting festa root category to its store group
        $festa = $this->groupFactory->create();
        $this->groupResourceModel->load($festa, CreateWebsites::FESTA_GROUP_CODE, 'code');
        $festa->setRootCategoryId($festaId);
        $this->groupResourceModel->save($festa);

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

    private function createCategories($nome): void
    {
        $category = $this->categoryFactory->create();
        $category->isObjectNew(true);
        $category->setName($nome)
            ->setParentId(Category::TREE_ROOT_ID)
            ->setIsActive(true);
        $this->categoryRepository->save($category);
    }
}
