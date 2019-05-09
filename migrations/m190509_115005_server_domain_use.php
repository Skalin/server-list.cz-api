<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190509_115005_server_domain_use extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$this->addColumn('{{server}}', 'use_domain', 'boolean DEFAULT 0');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'use_domain');
    }
}
