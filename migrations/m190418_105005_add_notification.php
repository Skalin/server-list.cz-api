<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m190418_105005_add_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->createTable('{{user_notification}}', [
    		'id' => $this->primaryKey(),
		    'user_id' => $this->integer(),
		    'read' => $this->tinyInteger()->defaultValue(0),
		    'title' => $this->string(),
		    'date' => $this->dateTime(),
		    'content' => $this->text(),
		    'objectArray' => $this->text()
	    ]);
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{login_token}}', 'issue_date');
    }
}
