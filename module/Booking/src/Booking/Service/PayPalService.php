<?php

namespace Booking\Service;

use Base\Manager\ConfigManager;
use Base\Manager\OptionManager;
use Base\Service\AbstractService;
use RuntimeException;
use Zend\Json\Json;

class PayPalService extends AbstractService
{

    protected $configManager;
    protected $optionManager;

    protected $access;

    public function __construct(ConfigManager $configManager, OptionManager $optionManager)
    {
        $this->configManager = $configManager;
        $this->optionManager = $optionManager;
    }

    public function setAccess($access)
    {
        $this->access = $access;
    }

    public function getAccess()
    {
        throw new RuntimeException('PayPal payment not yet implemented');
    }

    public function getPayment(array $data)
    {
        throw new RuntimeException('PayPal payment not yet implemented');
    }

    public function executePayment($access = null, $paymentId = null, $payerId = null)
    {
        throw new RuntimeException('PayPal payment not yet implemented');
    }

}