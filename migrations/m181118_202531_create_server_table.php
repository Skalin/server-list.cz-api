<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m181118_202531_create_server_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{server}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string(100)->notNull(),
        	'domain' => $this->string(100)->null(),
        	'ip' => $this->string(15)->null(),
        	'password' => $this->string('150')->null(),
			'port' => $this->integer(5)->null(),
			'query_port' => $this->integer(5)->null(),
			'service_id' => $this->integer()->notNull(),
			'registrator_id' => $this->integer(),
        ]);

        $this->addForeignKey('fk_server_service', '{{server}}', 'service_id', '{{service}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_server_service', '{{server}}');
        $this->dropTable('{{server}}');
    }
}
