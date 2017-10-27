<?php

namespace Mofluid\Payment\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * Data upgrade
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();
        if ($context->getVersion()
            && version_compare($context->getVersion(), '1.1.2') < 0
        ) {
            $connection->update(
                $setup->getTable('mofluid_payment_items'),
                [
                    'payment_method_code' => \Magedelight\Stripe\Model\Payment::CODE,
                    'payment_method_order_code' => \Magedelight\Stripe\Model\Payment::CODE
                ],
                [
                    'id = ?' => 9,
                ]
            );
            $connection->update(
                $setup->getTable('mofluid_payment_items'),
                [
                    'payment_method_code' => \Magento\Paypal\Model\Config::METHOD_EXPRESS,
                    'payment_method_order_code' => \Magento\Paypal\Model\Config::METHOD_EXPRESS
                ],
                [
                    'id = ?' => 10,
                ]
            );
            $connection->insertForce(
                $setup->getTable('mofluid_payment_items'),
                [
                    'id' => 11,
                    'payment_method_title' => 'Paypal Express Checkout REST API',
                    'payment_method_code' => 'paypal_rest_api',
                    'payment_method_order_code' => 'paypal_rest_api',
                    'payment_method_status' => 0,
                    'payment_method_mode' => 0
                ]
            );
        }
    }
}