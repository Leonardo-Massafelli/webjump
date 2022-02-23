<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class FixCategoriesUrl implements DataPatchInterface
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
        $party = $this->storeRepository->get(CreateWebsites::FESTA_STORE_CODE)->getId();
        $datas = $this->dataParty();

        foreach ($datas as $data){
            $id = $this->getCategoryId($data['original-name']);

            $category = $this->categoryRepository->get($id,$party);
            $category->setUrlKey($data['url'])->save();

        }


        $automotive = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE)->getId();
        $datas = $this->dataAutomotive();

        foreach ($datas as $data){
            $id = $this->getCategoryId($data['original-name']);

            $category = $this->categoryRepository->get($id,$automotive);
            $category->setUrlKey($data['url'])->save();

        }
    }
    public function dataParty()
    {
        return [
            [
                'original-name' => 'Datas comemorativas',
                'url' => 'datascomemorativas'
            ],
            [
                'original-name' => 'Festa temática',
                'url' => 'festatematica'
            ],
            [
                'original-name' => 'Festa infantil',
                'url' => 'festainfantil'
            ],
            [
                'original-name' => 'Balões e bexigas',
                'url' => 'baloes-bexigas'
            ],
            [
                'original-name' => 'Decoração',
                'url' => 'decoracao'
            ],
        ];
    }

    public function dataAutomotive()
    {
        return [
            [
                'original-name' => 'Volt 3',
                'url' => 'volt-3-en'
            ],
            [
                'original-name' => 'Volt SX',
                'url' => 'volt-sx-en'
            ],
            [
                'original-name' => 'roadmaster',
                'url' => 'roadmaster-en'
            ],
            [
                'original-name' => 'Acessórios',
                'url' => 'acessories-en'
            ]
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
