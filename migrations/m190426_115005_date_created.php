<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190426_115005_date_created extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('{{server}}', 'created_at', 'datetime DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('{{server}}', 'updated_at', 'datetime');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'created_at');
		$this->dropColumn('{{service}}', 'updated_at');
    }
}
