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
    	$this->addColumn('{{server}}', 'image_url', $this->string());
    	$this->addColumn('{{server}}', 'thumbnail_image_url', $this->string());
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{server}}', 'image_url');
	    $this->dropColumn('{{server}}', 'background_image_url');
    }
}
