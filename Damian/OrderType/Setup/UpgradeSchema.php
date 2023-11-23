<?php
namespace Damian\OrderType\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

include_once 'AbstractSchema.php';

class UpgradeSchema implements UpgradeSchemaInterface 
{
    public function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->startSetup();
        $connection = $installer->getConnection();

        $orderType = new Order_Type_Schema();
        $foreignKey = $orderType->getPrimaryKey();
        $salesOrder = new Sales_Order_Schema($foreignKey);

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            if ($connection->isTableExists($orderType->getName()) == true) {
                $orderType->dropTable($connection);
            }
            if($connection->tableColumnExists($salesOrder->getName(), $foreignKey)) {
                $salesOrder->dropColumn($connection);
            }
        } else {
            if ($connection->isTableExists($orderType->getName()) != true) {
                $orderType->createTable($connection);
            }
            if ($orderType->isTableEmpty($connection)) {
                $orderType->insertRecords($connection);
            }
            if(!$connection->tableColumnExists($salesOrder->getName(), $foreignKey)) {
                $salesOrder->addColumn($connection);
            }
        }
        
        $installer->endSetup();
    }
}