<?php

namespace Webjump\Configuration\Setup\Patch\Data;

use Laminas\Filter\ToInt;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory;
use Magento\Store\Model\ScopeInterface;

class ChangeUrls implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        WriterInterface $writer,
        CollectionFactory $collectionFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->writer = $writer;
        $this->collectionFactory = $collectionFactory;
    }


    public static function getDependencies()
    {
        return [CreateWebsites::class];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->writer->save('web/unsecure/base_url', 'http://admin.develop.com.br/', 'default', 0);
        $this->writer->save('web/unsecure/base_link_url', 'http://admin.develop.com.br/', 'default', 0);

        $collection = $this->collectionFactory->create()->addFieldToFilter('name', 'Festa')->setPageSize(1);

        if ($collection->getSize()){
            $websiteFestaId = $collection->getFirstItem()->getId();
        }

        $this->writer->save('web/unsecure/base_url', 'http://festa.develop.com.br/', 'websites', $websiteFestaId);
        $this->writer->save('web/unsecure/base_link_url', 'http://festa.develop.com.br/', 'websites', $websiteFestaId);

        $this->moduleDataSetup->getConnection()->endSetup();

        $collection = $this->collectionFactory->create()->addFieldToFilter('name', 'Automotivo')->setPageSize(1);

        if ($collection->getSize()){
            $websiteAutomotivoId = $collection->getFirstItem()->getId();
        }

        $this->writer->save('web/unsecure/base_url', 'http://automotivo.develop.com.br/', 'websites', $websiteAutomotivoId);
        $this->writer->save('web/unsecure/base_link_url', 'http://automotivo.develop.com.br/', 'websites', $websiteAutomotivoId);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
