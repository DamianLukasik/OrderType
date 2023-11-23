<?php
namespace Damian\OrderType\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

include_once 'AbstractSchema.php';

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->startSetup();
        $connection = $installer->getConnection();

        $orderType = new Order_Type_Schema();
        $orderType->createTable($connection);
        $orderType->insertRecords($connection);
        
        $salesOrder = new Sales_Order_Schema($orderType->getPrimaryKey());
        $salesOrder->addColumn($connection);
        
        $installer->endSetup();
    }
}