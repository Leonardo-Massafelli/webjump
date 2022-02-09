<?php
/*
 *  @category  	Techflarestudio
 *  @author	Wasalu Duckworth
 *  @copyright Copyright (c) 2021 Techflarestudio, Ltd. 			(https://techflarestudio.com)
 *  @license http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use \Magento\Cms\Model\BlockFactory;
use Magento\Store\Api\StoreRepositoryInterface;


/**
 * Class UpdateBlockData
 * @package Techflarestudio\Content\Setup\Patch\Data
 */
class FooterLinks2
    implements DataPatchInterface,
    PatchRevertableInterface
{
    const BLOCK_IDENTIFIER = 'footer_links_block2';
    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * UpdateBlockData constructor.
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory,
        StoreRepositoryInterface $storeRepository
    ) {
        $this->blockFactory = $blockFactory;
        $this->storeRepository = $storeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {

        $automotivo = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_STORE_CODE);
        $automotivo_en = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE);

        $headerNoticeData = [
            'title' => 'footer links block2',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '<div class="footer-links">
            <h3>Shop</h3>
            <ul>
            <li><a href="#">Exclusive offers</a></li>
            <li><a href="#">Find a store</a></li>
            <li><a href="#">Inventory</a></li>
            <li><a href="#">Certified pre-owner </a></li>
            <li><a href="#">Brochures</a></li>
            </ul>
            </div>',
            'stores' => [$automotivo->getId(),$automotivo_en->getId()],
            'is_active' => 1,
        ];
        $headerNoticeBlock = $this->blockFactory
            ->create()
            ->load($headerNoticeData['identifier'], 'identifier');

        /**
         * Create the block if it does not exists, otherwise update the content
         */
        if (!$headerNoticeBlock->getId()) {
            $headerNoticeBlock->setData($headerNoticeData)->save();
        } else {
            $headerNoticeBlock->setContent($headerNoticeData['content'])->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        /**
         * No dependencies for this
         */
        return [
            CreateWebsites::class 
        ];
    }

    /**
     * Delete the block
     */
    public function revert()
    {
        $headerNoticeBlock = $this->blockFactory
            ->create()
            ->load(self::BLOCK_IDENTIFIER, 'identifier');

        if($headerNoticeBlock->getId()) {
            $headerNoticeBlock->delete();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * Aliases are useful if we change the name of the patch until then we do not need any
         */
        return [];
    }
}