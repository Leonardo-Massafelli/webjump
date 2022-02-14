<?php
namespace Webjump\Configuration\Setup\Patch\Data;

use DomainException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\File\Csv;
use Magento\Setup\Module\Setup;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Webjump\Configuration\Setup\Patch\Data\CreateWebsites;
use Magento\Store\Api\StoreRepositoryInterface;




class ConfigTableRates implements DataPatchInterface
{

    CONST TABLE_RATES_FILE = __DIR__ . '/uploads/tablerates.csv';
    CONST TABLE_SHIPPING = 'shipping_tablerate';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var Csv
     */
    private $csv;
    /**
     * @var Setup
     */
    private $setup;
    /**
     * @var WebsiteRepositoryInterface
     */
    private $websiteRepositoryInterface;
    private $storeRepository;


    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $configInterface,
        Csv $csv,
        Setup $setup,
        WebsiteRepositoryInterface $websiteRepositoryInterface,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configInterface = $configInterface;
        $this->csv = $csv;
        $this->setup = $setup;
        $this->websiteRepositoryInterface = $websiteRepositoryInterface;
        $this->storeRepository = $storeRepository;
    }


    public function importTable ($file) : void {
        $csvdata = $this->csv->getData($file);

        $columns = $csvdata[0];
        unset($csvdata[0]);
        $datas = array_values($csvdata);

        $i = 0;

        foreach ($datas as $value) {
            $datas[$i][0] = $this->websiteRepositoryInterface->get($value[0])->getId();
            $i++;
        }


        $this->setup->getConnection()->insertArray(self::TABLE_SHIPPING, $columns, $datas);
    }
    public function DataConfig() : array {
        return [
            ['carriers/tablerate/active', true],
            ['carriers/tablerate/title', 'Webjump Entregas'],
            ['carriers/tablerate/name', 'Método de entregas da Webjump'],
            ['carriers/tablerate/condition_name', 'package_value_with_discount'],
            ['carriers/tablerate/include_virtual_price', true],
            ['carriers/tablerate/handling_type', 'F'],
            ['carriers/tablerate/handling_fee', '6.0'],
            ['carriers/tablerate/specificerrmsg', 'Esse método de entrega não está disponível no momento'],
            ['carriers/tablerate/sallowspecific', true],
            ['carriers/tablerate/specificcountry', 'BR,US'],
            ['carriers/tablerate/sort_order', 0],
        ];
    }


    public function DataConfigEn() : array {
        return [
            ['carriers/tablerate/title', 'Webjump Delivers'],
            ['carriers/tablerate/name', 'Webjump Delivers Method'],
            ['carriers/tablerate/specificerrmsg', 'This delivery method is currently not available'],
        ];
    }


    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $arry_data = $this->DataConfig();

        foreach ($arry_data as $data) {
            $this->configInterface->saveConfig($data[0], $data[1]);
        }

        $this->importTable(self::TABLE_RATES_FILE);

        $transLabel = $this->DataConfigEn();

        $automotivoEN = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE)->getId();
        foreach ($transLabel as $label){
            $this->configInterface->saveConfig(
                $label[0],
                $label[1],
                ScopeInterface::SCOPE_STORES,
                $automotivoEN
            );
        }

        $festaEN = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();
        foreach ($transLabel as $label){
            $this->configInterface->saveConfig(
                $label[0],
                $label[1],
                ScopeInterface::SCOPE_STORES,
                $festaEN
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();

    }

    public static function getDependencies()
    {
        return [
            CreateWebsites::class
        ];
    }

    public function getAliases()
    {
        return [];
    }

}
