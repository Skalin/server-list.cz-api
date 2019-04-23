<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190317_161649_update_server_add_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{server}}', 'image_url', 'string');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{server}}', 'image_url');
    }
}
