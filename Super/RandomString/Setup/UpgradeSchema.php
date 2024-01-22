<?php
namespace Super\RandomString\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Zend_Db_Exception;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @throws NoSuchEntityException
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ): void
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.2.1', '<'))
        {
            if (!$installer->tableExists('random_table'))
            {
                try {
                    $table = $installer->getConnection()->newTable(
                        $installer->getTable('random_table')
                    )
                        ->addColumn(
                            'id_random',
                            Table::TYPE_INTEGER,
                            null,
                            [
                                'identity' => true,
                                'nullable' => false,
                                'primary' => true,
                                'unsigned' => true,
                            ],
                            'ID'
                        )
                        ->addColumn(
                            'random_string',
                            Table::TYPE_TEXT,
                            255,
                            ['nullable => false'],
                            'Random String'
                        )
                        ->setComment('Random Table');
                } catch (Zend_Db_Exception $e) {
                    throw new NoSuchEntityException(__($e->getMessage()));
                }
                try {
                    $installer->getConnection()->createTable($table);
                } catch (Zend_Db_Exception $e) {
                    throw new NoSuchEntityException(__($e->getMessage()));
                }
                $installer->getConnection()->addIndex(
                    $installer->getTable('random_table'),
                    $setup->getIdxName(
                        $installer->getTable('random_table'),
                        ['random_string'],
                        AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['random_string'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }
        $installer->endSetup();
    }
}
