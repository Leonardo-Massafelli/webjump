<?php

namespace Webjump\Configuration\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;

class AdditionalConfigVars implements ConfigProviderInterface
{
    public function getConfig()
    {
        $additionalVariables['test_var'] = 'Test Var';
        return $additionalVariables;
    }
}
