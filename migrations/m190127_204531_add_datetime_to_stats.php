<?php

use yii\db\Migration;

/**
 * Handles the creation of table `statistic_players`.
 */
class m190127_204531_add_datetime_to_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->addColumn('{{statistic_status}}', 'date', 'datetime DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('{{statistic_ping}}', 'date', 'datetime DEFAULT CURRENT_TIMESTAMP');
		$this->addColumn('{{statistic_players}}', 'date', 'datetime DEFAULT CURRENT_TIMESTAMP');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		$this->dropColumn('{{statistic_status}}', 'date');
		$this->dropColumn('{{statistic_ping}}', 'date');
		$this->dropColumn('{{statistic_players}}', 'date');
    }
}
