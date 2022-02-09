<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigLocaleIdiom implements DataPatchInterface
{
    private $moduleDataSetup;
    private $storeRepository;
    private $config;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StoreRepositoryInterface $storeRepository,
        ConfigInterface $config
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->storeRepository = $storeRepository;
        $this->config = $config;
    }

    public static function getDependencies()
    {
        return[CreateWebsites::class];
    }

    public function getAliases()
    {
        return[];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->config->saveConfig(
            'system/currency/installed',
            'BRL,USD',
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );

        //Configuring locale for automotivo
        $automotivoEn = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE)->getId();

        $this->config->saveConfig(
            'general/locale/code',
            'en_US',
            ScopeInterface::SCOPE_STORES,
            $automotivoEn
        );

        $this->config->saveConfig(
            'currency/options/allow',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $automotivoEn
        );

        $this->config->saveConfig(
            'currency/options/default',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $automotivoEn
        );

        //Configuring locale for festa
        $festaEn = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();

        $this->config->saveConfig(
            'general/locale/code',
            'en_US',
            ScopeInterface::SCOPE_STORES,
            $festaEn
        );

        $this->config->saveConfig(
            'currency/options/allow',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $festaEn
        );

        $this->config->saveConfig(
            'currency/options/default',
            'USD',
            ScopeInterface::SCOPE_STORES,
            $festaEn
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
