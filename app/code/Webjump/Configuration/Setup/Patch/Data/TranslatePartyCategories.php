<?php

namespace Webjump\Configuration\Setup\Patch\Data;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class TranslatePartyCategories implements DataPatchInterface
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
        $partyEN = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();
        $datas = $this->data();

        foreach ($datas as $data){
            $id = $this->getCategoryId($data['original-name']);

            $category = $this->categoryRepository->get($id,$partyEN);
            $category-> setName($data['name'])
                -> setMetaTitle($data['meta'])
                -> setUrlKey($data['url'])
                -> save();

        }
    }
    public function data()
    {
        $commemorativedatesId = $this->getCategoryId('Datas Comemorativas');
        $themedPartyId = $this->getCategoryId('Festa temática');
        $childrensPartyId = $this->getCategoryId('Festa Infantil');
        $baloonsId = $this->getCategoryId('Balões e Bexigas');
        $decorationsId = $this->getCategoryId('Decoração');

        return [
            [
                'original-name' => 'Datas Comemorativas',
                'name' => 'Commemorative Dates',
                'parent' => null,
                'meta' => 'Festa | Commemorative Dates',
                'url' => 'commemorative-dates'
            ],
            [
                'original-name' => 'Festa temática',
                'name' => 'Themed Party',
                'parent' => null,
                'meta' => 'Festa | Themed Party',
                'url' => 'themed-party'
            ],
            [
                'original-name' => 'Festa infantil',
                'name' => "Children's Party",
                'parent' => null,
                'meta' => "Festa | Children's Party",
                'url' => 'childrens-party'
            ],
            [
                'original-name' => 'Balões e bexigas',
                'name' => 'Baloons',
                'parent' => null,
                'meta' => 'Festa | Baloons',
                'url' => 'baloons'
            ],
            [
                'original-name' => 'Decoração',
                'name' => 'Decorations',
                'parent' => null,
                'meta' => 'Festa | Decorations',
                'url' => 'decorations'
            ],

            /* SUBCATEGORIES of Commemorative Dates */
            [
                'original-name' => 'Páscoa',
                'name' => 'Easter',
                'parent' => $commemorativedatesId,
                'meta' => 'Festa | Commemorative Dates - Easter',
                'url' => 'easter'
            ],
            [
                'original-name' => 'Natal',
                'name' => 'Christmas',
                'parent' => $commemorativedatesId,
                'meta' => 'Festa | Commemorative Dates - Christmas',
                'url' => 'christmas'
            ],
            [
                'original-name' => 'Festa Junina',
                'name' => 'Feast of Saint John',
                'parent' => $commemorativedatesId,
                'meta' => 'Festa | Commemorative Dates - Feast of Saint John',
                'url' => 'feast-of-saint-john'
            ],
            [
                'original-name' => 'Carnaval',
                'name' => 'Carnival',
                'parent' => $commemorativedatesId,
                'meta' => 'Festa | Commemorative Dates - Carnival',
                'url' => 'carnival'
            ],
            [
                'original-name' => 'Aniversário',
                'name' => 'Birthday',
                'parent' => $commemorativedatesId,
                'meta' => 'Festa | Commemorative Dates - Birthday',
                'url' => 'birthday'
            ],

            /*SUBCATEGORIE OF Themed Party*/
            [
                'original-name' => 'Festa a Fantasia',
                'name' => 'Costume Party',
                'parent' => $themedPartyId,
                'meta' => 'Festa | Themed Party - Costume Party',
                'url' => 'costume-party'
            ],
            [
                'original-name' => 'Super-Heróis',
                'name' => 'Super Heroes',
                'parent' => $themedPartyId,
                'meta' => 'Festa | Themed Party - Super Heroes',
                'url' => 'super-heroes'
            ],

            /* SUBCATEGORIES OF CHILDRENS PARTY */
            [
                'original-name' => 'Fantasias',
                'name' => 'Costumes',
                'parent' => $childrensPartyId,
                'meta' => "Festa | Children's Party - Costumes",
                'url' => 'costumes'
            ],

            /* SUBCATEGORIES OF BALOONS */
            [
                'original-name' => 'Balões',
                'name' => 'Baloons',
                'parent' => $baloonsId,
                'meta' => 'Festa | Baloons - Baloons',
                'url' => 'baloons'
            ],
            [
                'original-name' => 'Bexigas Simples',
                'name' => 'Simple Baloons',
                'parent' => $baloonsId,
                'meta' => 'Festa | Baloons - Simple Baloons',
                'url' => 'simple-baloons'
            ],
            [
                'original-name' => 'Bexigas Customizadas',
                'name' => 'Customized Baloons',
                'parent' => $baloonsId,
                'meta' => 'Festa | Baloons - Customized Baloons',
                'url' => 'customized-baloons'
            ],
            /* SUBCATEGORIES OF DECORATIONS  */
            [
                'original-name' => 'Painéis',
                'name' => 'Decorative Panels',
                'parent' => $decorationsId,
                'meta' => 'Festa | Decorations - Decorative Panels',
                'url' => 'decorative-panels'
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
