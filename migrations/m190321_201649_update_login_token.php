<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190321_201649_update_login_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{login_token}}', 'issue_date', 'datetime');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{login_token}}', 'issue_date');
    }
}
