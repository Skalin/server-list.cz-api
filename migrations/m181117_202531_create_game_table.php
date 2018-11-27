<?php

use yii\db\Migration;

/**
 * Handles the creation of table `game`.
 */
class m181117_202531_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('game', [
            'id' => $this->primaryKey(),
			'name' => $this->string()->notNull(),
        	'description' => $this->text(),
        	'position' => $this->integer()->notNull(),
			'active' => $this->boolean(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('game');
    }
}
