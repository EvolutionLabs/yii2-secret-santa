<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%secret_santa_list_pair}}`.
 */
class m201031_140223_create_secret_santa_list_pair_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%secret_santa_list_pair}}', [
            'id'          => $this->bigPrimaryKey()->unsigned(),
            'list_id'     => $this->bigInteger()->unsigned()->notNull(),
            'status'      => "ENUM('not-sent', 'sent', 'failed')",
            'giver_id'    => $this->bigInteger()->unsigned()->notNull(),
            'receiver_id' => $this->bigInteger()->unsigned()->notNull(),
            'created_at'  => $this->datetime()->notNull(),
            'updated_at'  => $this->datetime()->notNull(),
        ], $tableOptions);

        // creates index for column `list_id`
        $this->createIndex(
            'idx-secret_santa_list_pair-list_id',
            '{{%secret_santa_list_pair}}',
            'list_id'
        );

        // add foreign key for table `secret_santa_list`
        $this->addForeignKey(
            'fk-secret_santa_list_pair-list_id',
            '{{%secret_santa_list_pair}}',
            'list_id',
            '{{%secret_santa_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `giver_id`
        $this->createIndex(
            'idx-secret_santa_list_pair-giver_id',
            '{{%secret_santa_list_pair}}',
            'giver_id'
        );

        // add foreign key for table `secret_santa_list_member`
        $this->addForeignKey(
            'fk-secret_santa_list_pair-giver_id',
            '{{%secret_santa_list_pair}}',
            'giver_id',
            '{{%secret_santa_list_member}}',
            'id',
            'CASCADE'
        );

        // creates index for column `receiver_id`
        $this->createIndex(
            'idx-secret_santa_list_pair-receiver_id',
            '{{%secret_santa_list_pair}}',
            'receiver_id'
        );

        // add foreign key for table `secret_santa_list_member`
        $this->addForeignKey(
            'fk-secret_santa_list_pair-receiver_id',
            '{{%secret_santa_list_pair}}',
            'receiver_id',
            '{{%secret_santa_list_member}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-secret_santa_list_pair_unique',
            '{{%secret_santa_list_pair}}',
            ['list_id', 'giver_id', 'receiver_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%secret_santa_list_pair}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();

    }
}
