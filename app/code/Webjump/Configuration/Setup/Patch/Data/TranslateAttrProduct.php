<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Api\Data\AttributeFrontendLabelInterfaceFactory;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class TranslateAttrProduct implements DataPatchInterface {

    private $moduleDataSetup;
    private $eavSetupFactory;
    private $productAttributeRepository;
    private $attributeFrontendLabelInterfaceFactory;
    private $storeRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        AttributeFrontendLabelInterfaceFactory $attributeFrontendLabelInterfaceFactory,
        StoreRepositoryInterface $storeRepository

    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->attributeFrontendLabelInterfaceFactory = $attributeFrontendLabelInterfaceFactory;
        $this->storeRepository = $storeRepository;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->translateLabel(CreateWebsites::FESTA_EN_STORE_CODE, AddFestaAttributeProducts::FESTA_TEMA, 'Theme');
        $this->translateLabel(CreateWebsites::FESTA_EN_STORE_CODE, AddFestaAttributeProducts::FESTA_QTD, 'Quantity per package');

        $this->translateLabel(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE, AddAutomotivoAttributeProducts::AUTOMOTIVO_AUTO, 'Automatic');
        $this->translateLabel(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE, AddAutomotivoAttributeProducts::AUTOMOTIVO_AR_CONDICIONADO, 'Air conditioning');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function translateLabel($storeNameEn, $nameAttribute, $LabelEn)
    {
        $storeEn = $this->storeRepository->get($storeNameEn)->getId();
        $attribute = $this->productAttributeRepository->get($nameAttribute);

        $frontendLabels = [
            $this->attributeFrontendLabelInterfaceFactory->create()
                ->setStoreId($storeEn)
                ->setLabel($LabelEn),
        ];
        $attribute->setFrontendLabels($frontendLabels);
        $this->productAttributeRepository->save($attribute);
    }

    public static function getDependencies():array
    {
        return [];
    }

    public function getAliases():array
    {
        return [];
    }
}
