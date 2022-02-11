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
use Webjump\Configuration\Setup\Patch\Data\CreateWebsites;


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



    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        ConfigInterface $configInterface,
        Csv $csv,
        Setup $setup,
        WebsiteRepositoryInterface $websiteRepositoryInterface
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->configInterface = $configInterface;
        $this->csv = $csv;
        $this->setup = $setup;
        $this->websiteRepositoryInterface = $websiteRepositoryInterface;
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
            ['carriers/tablerate/title', 'Webjump Delivery'],
            ['carriers/tablerate/name', 'Webjump Delivery Method'],
            ['carriers/tablerate/condition_name', 'package_value_with_discount'],
            ['carriers/tablerate/include_virtual_price', true],
            ['carriers/tablerate/handling_type', 'F'],
            ['carriers/tablerate/handling_fee', '6.0'],
            ['carriers/tablerate/specificerrmsg', 'This shipping method is not available. To use this shipping method, please contact us'],
            ['carriers/tablerate/sallowspecific', true],
            ['carriers/tablerate/specificcountry', 'BR,US'],
            ['carriers/tablerate/sort_order', 0],
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
