<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class AddMissingSubCategories implements DataPatchInterface
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

        $subcategorias = ['Festa Junina', 'Carnaval', 'Aniversário'];

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
