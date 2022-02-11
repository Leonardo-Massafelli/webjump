<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\ConfigResource\ConfigInterface as ConfigResourceConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\ScopeInterface;


class CreatePay implements DataPatchInterface{

    private $moduleDataSetup;
    private $configInterface;
    private $storeRepository;

    private $automotive_en_id;
    private $party_en_id;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetupInterface,
        ConfigResourceConfigInterface $configInterface,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetupInterface;
        $this->configInterface = $configInterface;
        $this->storeRepository = $storeRepository;
    }

public function apply()
{
    $this->moduleDataSetup->getConnection()->startSetup();
     
    $this->automotive_en_id = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE)->getId();
    $this->party_en_id = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();

        $data = $this->getData();

        foreach ($data as $config) {
            $this->configInterface->saveConfig(
                $config['path'],
                $config['value'],
                $config['scope'],
                $config['scopeId']
            );
        }
    $this->moduleDataSetup->getConnection()->endSetup();
}

private function getData()
    {
        return [
            'banktransaactive' => [
                'path' => 'payment/banktransfer/active',
                'value' => true,
                'scope' => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                'scopeId' => '0'
            ],
            'banktranstitle' => [
                'path' => 'payment/banktransfer/title',
                'value' => 'Transferência Bancária',
                'scope' => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                'scopeId' => '0'
            ], 
            'banktranstitlea_en' => [
                'path' => 'payment/banktransfer/title',
                'value' => 'Bank Transfer',
                'scope' => ScopeInterface::SCOPE_STORES,
                'scopeId' => $this->automotive_en_id
            ],
            'banktranstitlep_en' => [
                'path' => 'payment/banktransfer/title',
                'value' => 'Bank Transfer',
                'scope' => ScopeInterface::SCOPE_STORES,
                'scopeId' => $this->party_en_id
            ],
            'checkmoactive' => [
                'path' => 'payment/checkmo/active',
                'value' => true,
                'scope' => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                'scopeId' => '0'
            ],
            'checkmotitle' => [
                'path' => 'payment/checkmo/title',
                'value' => 'Cheque ou Dinheiro',
                'scope' => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                'scopeId' => '0'
            ],
            'checkmoactivea_en' => [
                'path' => 'payment/checkmo/title',
                'value' => 'Check/Money Order',
                'scope' => ScopeInterface::SCOPE_STORES,
                'scopeId' => $this->automotive_en_id
            ],
            'checkmoactivep_en' => [
                'path' => 'payment/checkmo/title',
                'value' => 'Check/Money Order',
                'scope' => ScopeInterface::SCOPE_STORES,
                'scopeId' => $this->party_en_id
            ],
        ];
    }

public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}