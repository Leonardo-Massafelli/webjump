<?php

declare(strict_types=1);

namespace Webjump\Configuration\Setup\Patch\Data;

use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionLabelInterface;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\StoreRepositoryInterface;


class AddAttributeOptionColor implements DataPatchInterface
{
    /**
     * @var AttributeOptionInterfaceFactory
     */
    private $optionInterfaceFactory;
    /**
     * @var AttributeOptionManagementInterface
     */
    private $optionManagement;
    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    private $optionLabelInterfaceFactory;
    private $storeRepository;

    public function __construct(
        AttributeOptionInterfaceFactory $optionInterfaceFactory,
        AttributeOptionManagementInterface $optionManagement,
        AttributeOptionLabelInterfaceFactory $optionLabelInterfaceFactory,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->optionInterfaceFactory = $optionInterfaceFactory;
        $this->optionManagement = $optionManagement;
        $this->optionLabelInterfaceFactory = $optionLabelInterfaceFactory;
        $this->storeRepository = $storeRepository;
    }

    public function apply()
    {
        $partyEnId = $this->storeRepository->get(CreateWebsites::FESTA_EN_STORE_CODE)->getId();
        $automotiveEnId = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_EN_STORE_CODE)->getId();
        $partyId = $this->storeRepository->get(CreateWebsites::FESTA_STORE_CODE)->getId();
        $automotiveId = $this->storeRepository->get(CreateWebsites::AUTOMOTIVO_STORE_CODE)->getId();

        $options = [
            [
                'label' => 'blue',
                'value' => 'blue',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Blue',
                        'store_id' => $partyEnId
                    ],
                    [
                        'label' => 'Blue',
                        'store_id' => $automotiveEnId
                    ],
                    [
                        'label' => 'Azul',
                        'store_id' => $partyId
                    ],
                    [
                        'label' => 'Azul',
                        'store_id' => $automotiveId
                    ]
                ]
            ],
            [
                'label' => 'red',
                'value' => 'red',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Red',
                        'store_id' => $partyEnId
                    ],
                    [
                        'label' => 'Red',
                        'store_id' => $automotiveEnId
                    ],
                    [
                        'label' => 'Vermelho',
                        'store_id' => $partyId
                    ],
                    [
                        'label' => 'Vermelho',
                        'store_id' => $automotiveId
                    ]
                ]
            ],
            [
                'label' => 'yellow',
                'value' => 'yellow',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Yellow',
                        'store_id' => $partyEnId
                    ],
                    [
                        'label' => 'Yellow',
                        'store_id' => $automotiveEnId
                    ],
                    [
                        'label' => 'Amarelo',
                        'store_id' => $partyId
                    ],
                    [
                        'label' => 'Amarelo',
                        'store_id' => $automotiveId
                    ]
                ]
            ],
            [
                'label' => 'random',
                'value' => 'random',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'Random',
                        'store_id' => $partyEnId
                    ],
                    [
                        'label' => 'Random',
                        'store_id' => $automotiveEnId
                    ],
                    [
                        'label' => 'Sortido',
                        'store_id' => $partyId
                    ],
                    [
                        'label' => 'Sortido',
                        'store_id' => $automotiveId
                    ]
                ]
            ],
            [
                'label' => 'white',
                'value' => 'white',
                'is_default' => false,
                'store_labels' => [
                    [
                        'label' => 'White',
                        'store_id' => $partyEnId
                    ],
                    [
                        'label' => 'White',
                        'store_id' => $automotiveEnId
                    ],
                    [
                        'label' => 'Branco',
                        'store_id' => $partyId
                    ],
                    [
                        'label' => 'Branco',
                        'store_id' => $automotiveId
                    ]

                ]
            ],
        ];

        foreach ($options as $op) {
            $option = $this->optionInterfaceFactory->create();
            $option->setLabel($op['label'])
                ->setValue($op['value']);
            $option->setIsDefault($op['is_default']);
            $labels = [];
            foreach ($op['store_labels'] as $la) {
                $label = $this->optionLabelInterfaceFactory->create();
                $label->setStoreId($la['store_id']);
                    $label->setLabel($la['label']);
                $labels[] = $label;
            }
            $option->setStoreLabels($labels);
            $this->optionManagement->add(
                4,
                'color',
                $option
            );
        }
    }

    public function getAliases()
    {
        return[];
    }


    public static function getDependencies()
    {
        return[
            CreateWebsites::class,
            AddAutomotivoAttributeProducts::class,
            AddFestaAttributeProducts::class,
        ];
    }
}
