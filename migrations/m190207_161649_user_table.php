<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m190207_161649_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->createTable('{{user}}', [
			'id' => $this->primaryKey(),
    		'username' => $this->string()->notNull()->unique(),
			'password' => $this->string()->notNull(),
			'auth_key' => $this->string()->notNull(),
			'registered' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
			'password_updated' => $this->dateTime()->defaultValue(null),
			'mail_updated' => $this->dateTime()->defaultValue(null),
			'salt' => $this->string()->notNull(),
			'mail' => $this->string()->notNull(),
			'name' => $this->string(),
			'surname' => $this->string(),
			'tos_agreement' => $this->boolean()->defaultValue(0),
			'gdpr_agreement' => $this->boolean()->defaultValue(0),
			'is_reviewer' => $this->integer()->defaultValue(0)

		]);

    	$this->createTable('{{registrator_token}}', [
    		'id' => $this->primaryKey(),
			'token' => $this->string()->unique(),
    		'user_id' => $this->integer(),
			'description' => $this->string(),
			'ip_list' => $this->text(),
			'active' => $this->boolean()->defaultValue(1),
			'expiration' => $this->dateTime(),
		]);

    	$this->createIndex('registrator_index', '{{registrator_token}}', 'token', true);
    	$this->addForeignKey('fk_registrator_token_user', '{{registrator_token}}', 'user_id', '{{user}}', 'id', 'CASCADE', 'CASCADE');

    	$this->createTable('{{login_token}}', [
			'id' => $this->primaryKey(),
			'token' => $this->string()->unique(),
    		'user_id' => $this->integer(),
			'expiration' => $this->dateTime()->notNull(),
		]);

		$this->createIndex('login_index', '{{login_token}}', 'token', true);
		$this->addForeignKey('fk_login_token_user', '{{login_token}}', 'user_id', '{{user}}', 'id', 'CASCADE', 'CASCADE');

	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	$this->dropForeignKey('fk_registrator_token_user', '{{registrator_token}}');
    	$this->dropTable('{{registrator_token}}');
		$this->dropForeignKey('fk_login_token_user', '{{login_token}}');
		$this->dropTable('{{login_token}}');
		$this->dropTable('{{user}}');
    }
}
