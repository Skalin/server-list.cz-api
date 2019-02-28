<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m190223_161649_update_server_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{server}}', 'user_id', 'integer');
    	$this->addForeignKey('fk_server_user', '{{server}}', 'user_id', '{{user}}', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('fk_server_registrator', '{{server}}', 'user_id', '{{user}}', 'id', 'SET NULL', 'SET NULL');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_server_registrator', '{{server}}');
		$this->dropForeignKey('fk_server_user', '{{server}}');
		$this->dropColumn('{{server}}', 'user_id');
    }
}
