<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190511_115005_server_domain_use extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('{{server}}', 'monitoring_chunk', 'integer DEFAULT 1');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'monitoring_chunk');
    }
}
