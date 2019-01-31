<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m181116_202531_create_category_table extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('{{category}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
			'position' => $this->integer()->notNull()->unique(),
		]);

		$this->insert('{{category}}', [
			'name' => 'Game',
			'position' => 1,
		]);

		$this->insert('{{category}}', [
			'name' => 'Comm server',
			'position' => 2,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('{{category}}');
	}
}
