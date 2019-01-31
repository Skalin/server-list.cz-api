<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistic_players`.
 */
class m190130_204531_add_stats_to_service extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{service}}', 'stats_list', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{service}}', 'stats_list');
    }
}
