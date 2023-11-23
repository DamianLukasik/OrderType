<?php
namespace Damian\OrderType\Setup;

use Magento\Framework\DB\Ddl\Table;
use \Magento\Framework\DB\Adapter\AdapterInterface;

class TableSchema
{
    protected $name = '';
    protected $primary_key = '';

    public function __construct($name, $primary_key) {
        $this->name = $name;
        $this->primary_key = $primary_key;
    }

    public function getPrimaryKey() {
        return $this->primary_key;
    }

    public function getName() {
        return $this->name;
    }

    public function isTableEmpty(AdapterInterface $connection)
    {
        return $connection->fetchOne($connection->select()->from($this->name, ['count' => 'COUNT(*)'])) == 0;
    }
}

class Order_Type_Schema extends TableSchema
{
    private $options_default = null;

    public function __construct() {
        parent::__construct('damian_ordertype_order_type', 'order_type_id');
        $this->options_default = [
            ['type_name' => 'Standardowe'],
            ['type_name' => 'Ekspozycyjne'],
            ['type_name' => 'Testowe']
        ];
    }

    public function createTable(AdapterInterface $connection) {
        $table = $connection->newTable($this->name)
        ->addColumn(
            $this->primary_key,
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Order Type ID'
        )->addColumn(
            'type_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Type Name'
        )->addColumn(
            'created',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Created'
        )->addColumn(
            'modified',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Modified'
        )->setComment(
            'Order Type Table'
        );
        $connection->createTable($table);
    }

    public function insertRecords(AdapterInterface $connection) {
        $connection->insertMultiple($this->name, $this->options_default);
    }

    public function dropTable(AdapterInterface $connection)
    {
        $connection->dropTable($this->name);
    }  
}

class Sales_Order_Schema extends TableSchema
{
    private $foreignKey = null; 

    public function __construct($foreignKey) {
        parent::__construct('sales_order', 'entity_id');
        $this->foreignKey = $foreignKey;
    }

    public function addColumn(AdapterInterface $connection)
    {
        $connection->addColumn(
            $this->name,
            $this->foreignKey,
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Order Type ID',
            ]
        );
    }   
    
    public function dropColumn(AdapterInterface $connection)
    {
        $connection->dropColumn($this->name, $this->foreignKey);
    }  
}