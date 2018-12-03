<?php

use yii\db\Migration;

/**
 * Handles the creation of table `service`.
 */
class m181117_202531_create_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{service}}', [
            'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
        	'description' => $this->text(),
        	'shortcut' => $this->string(20),
        	'position' => $this->integer()->notNull()->unique(),
			'active' => $this->boolean(),
			'category_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_service_category', '{{service}}', 'category_id', '{{category}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_service_category', '{{service}}');
        $this->dropTable('{{service}}');
    }
}
