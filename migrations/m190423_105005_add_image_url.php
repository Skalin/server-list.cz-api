<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190423_105005_add_image_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{service}}', 'image_url', $this->string());
    	$this->addColumn('{{service}}', 'thumbnail_image_url', $this->string());
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'image_url');
	    $this->dropColumn('{{service}}', 'background_image_url');
    }
}
