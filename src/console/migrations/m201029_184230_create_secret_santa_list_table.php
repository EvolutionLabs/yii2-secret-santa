<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%secret_santa_list}}`.
 */
class m201029_184230_create_secret_santa_list_table extends Migration
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

        $this->createTable('{{%secret_santa_list}}', [
            'id'      => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull(),
            'status'  => "ENUM('draft', 'sent', 'ready')",
            'name'    => $this->string(100)->notNull()->defaultValue('')
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-secret_santa_list-user_id',
            '{{%secret_santa_list}}',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-secret_santa_list-user_id',
            '{{%secret_santa_list}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%secret_santa_list}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
