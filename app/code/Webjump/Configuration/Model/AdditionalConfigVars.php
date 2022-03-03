<?php

namespace Webjump\Configuration\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;

class AdditionalConfigVars implements ConfigProviderInterface
{
    public function getConfig()
    {
        $additionalVariables['checkout_var'] = 'Checkout Var';
        return $additionalVariables;
    }
}
