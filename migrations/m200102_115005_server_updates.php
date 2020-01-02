<?php

use yii\db\Migration;

/**
 * Handles the creation of table `server`.
 */
class m200102_115005_server_updates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{server}}', 'players_value', 'integer DEFAULT NULL');
		$this->renameColumn('{{server}}', 'use_domain', 'show_port');
		$this->renameColumn('{{server}}', 'active', 'state');
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{server}}', 'players_value');
        $this->renameColumn('{{server}}', 'state', 'active');
		$this->renameColumn('{{server}}', 'show_port', 'use_domain');
    }
}
