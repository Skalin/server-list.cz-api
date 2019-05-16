<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190516_115005_server_reviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->createTable('{{review}}', [
			'id' => $this->primaryKey(),
			'title' => $this->string(),
			'content' => $this->text(),
			'rating' => $this->integer(4),
			'user_id' => $this->integer(),
		]);

		$this->addForeignKey('fk_user_reviews', '{{review}}','user_id', '{{user}}', 'id', 'CASCADE', 'CASCADE');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_user_reviews', '{{review}}');
    	$this->dropTable('{{review}}');
    }
}
