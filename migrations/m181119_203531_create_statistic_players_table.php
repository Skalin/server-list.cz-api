<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistic_players`.
 */
class m181119_203531_create_statistic_players_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{statistic_players}}', [
            'id' => $this->primaryKey(),
			'server_id' => $this->integer()->notNull(),
			'value' => $this->integer(5)->notNull(),
			'maxValue' => $this->integer(5)->notNull(),
        ]);

        $this->addForeignKey('fk_players_server', '{{statistic_players}}', 'server_id', '{{server}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_players_server', '{{statistic_players}}');
        $this->dropTable('{{statistic_players}}');
    }
}
