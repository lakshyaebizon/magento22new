<?php
/**
 * Copyright © 2015 Social. All rights reserved.
 */

namespace Mofluid\Notifications\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $connection = $installer->getConnection();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('mofluid_notifications_items'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
            'notification_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'notification_title'
            )
            ->addColumn(
                'pemfile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Pemfile'
            )
            ->addColumn(
                'passphrase',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Passphrase'
            )
             ->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Message'
            )
               ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'title'
            )
                ->addColumn(
                'fcm_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'fcm_key'
            );

        $installer->getConnection()->createTable($table);
        $connection->insertForce(
				$installer->getTable('mofluid_notifications_items'),
				[
					'id' => 1,
					'notification_title' => 'IOS Notification',
					'pemfile' => 0,
					'passphrase' => 0,
					'message' => null
				]
        );
          $connection->insertForce(
				$installer->getTable('mofluid_notifications_items'),
				[
					'id' => 2,
					'notification_title' => 'Android Notification',
					'fcm_key' => 0,
					'fcm_key' => 0,
					'message' => null
				]
        );

        $installer->getConnection()->createTable($table);
		
		$table1 = $installer->getConnection()->newTable( $installer->getTable('mofluidpuh') )
		->addColumn(
            'mofluidadmin_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'unsigned' => true, 'nullable' => false],
            'mofluidadmin_id'
        )
		->addColumn(
            'push_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['nullable' => false, 'default' => '0'],
			'push_id'
        )
		->addColumn(
            'device_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'device_id'
        )
		->addColumn(
            'push_token_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'push_token_id'
        )
		->addColumn(
            'platform',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'platform'
        )
		->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => ''],
            'description'
        )
		->addColumn(
            'app_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'app_name'
        )
		->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            ['default' => ''],
            'created_at'
        );
        $installer->endSetup();
    }
}
