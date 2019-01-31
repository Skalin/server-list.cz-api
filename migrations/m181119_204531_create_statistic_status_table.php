<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistic_players`.
 */
class m181119_204531_create_statistic_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{statistic_status}}', [
            'id' => $this->primaryKey(),
			'server_id' => $this->integer()->notNull(),
			'value' => $this->boolean()->notNull(),
        ]);

        $this->addForeignKey('fk_status_server', '{{statistic_status}}', 'server_id', '{{server}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_status_server', '{{statistic_status}}');
        $this->dropTable('{{statistic_status}}');
    }
}
