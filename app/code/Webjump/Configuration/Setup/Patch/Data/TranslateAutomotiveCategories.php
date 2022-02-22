<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class TranslateAutomotiveCategories implements DataPatchInterface
{
    private $moduleDataSetup;
    private $categoryRepository;
    private $storeRepository;
    private $collectionFactory;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategoryRepository $categoryRepository,
        StoreRepositoryInterface $storeRepository,
        CollectionFactory $collectionFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categoryRepository = $categoryRepository;
        $this->storeRepository = $storeRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $automotivePT = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_STORE_CODE)->getId();
        $datas = $this->data();

        foreach ($datas as $data){
            $id = $this->getCategoryId($data['original-name']);

            $category = $this->categoryRepository->get($id,$automotivePT);
            $category-> setName($data['name'])
                -> setMetaTitle($data['meta'])
                -> setUrlKey($data['url'])
                -> save();

        }
    }
    public function data()
    {
        $acessoriesId = $this->getCategoryId('Acessories');

        return [
            [
                'original-name' => 'Acessories',
                'name' => 'Acessórios',
                'parent' => null,
                'meta' => 'Automotivo | Acessórios',
                'url' => 'acessorios'
            ],

            /* SUBCATEGORIES of Acessórios */
            [
                'original-name' => 'Charging',
                'name' => 'Carregamento',
                'parent' => $acessoriesId,
                'meta' => 'Automotivo | Acessórios - Carregamento',
                'url' => 'carregamento'
            ],
            [
                'original-name' => 'Vehicle Acessories',
                'name' => 'Acessórios do veículo',
                'parent' => $acessoriesId,
                'meta' => 'Automotivo | Acessórios - Acessórios do veículo',
                'url' => 'acessorios-do-veiculo'
            ],
        ];
    }

    public static function getDependencies()
    {
        return [
            CreateCategories:: class,
            CreateWebsites::class,
            CreateRootCategories::class,
            CreateSubCategories::class
        ];
    }

    public function getAliases()
    {
        return [];
    }

    private function getCategoryId($nome)
    {
        $collection = $this->collectionFactory->create()->addAttributeToFilter('name', $nome)->setPageSize(1);

        if ($collection->getSize()){
            $categoryId = $collection->getFirstItem()->getId();
        }

        return $categoryId;
    }
}
