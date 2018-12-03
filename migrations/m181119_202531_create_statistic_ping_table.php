<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistic_ping`.
 */
class m181119_202531_create_statistic_ping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{statistic_ping}}', [
            'id' => $this->primaryKey(),
			'server_id' => $this->integer()->notNull(),
			'value' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_ping_server', '{{statistic_ping}}', 'server_id', '{{server}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_ping_server', '{{statistic_ping}}');
        $this->dropTable('{{statistic_ping}}');
    }
}
