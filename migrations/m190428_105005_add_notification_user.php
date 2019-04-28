<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190428_105005_add_notification_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addForeignKey('fk_notification_user', '{{user_notification}}', 'user_id', '{{user}}', 'id', 'CASCADE', 'CASCADE');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_notification_user', '{{user_notification}}');
    }
}
