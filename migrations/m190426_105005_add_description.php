<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190426_105005_add_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{server}}', 'description', $this->text());
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'description');
    }
}
