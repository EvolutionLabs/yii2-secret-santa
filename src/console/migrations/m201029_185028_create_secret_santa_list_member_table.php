<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%secret_santa_list_member_member}}`.
 */
class m201029_185028_create_secret_santa_list_member_table extends Migration
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

        $this->createTable('{{%secret_santa_list_member}}', [
            'id'      => $this->bigPrimaryKey()->unsigned(),
            'list_id' => $this->bigInteger()->unsigned()->notNull(),
            'name'    => $this->string(100)->notNull()->defaultValue(''),
            'email'   => $this->string(100)->notNull()->defaultValue(''),
            'address' => $this->string(255)->notNull()->defaultValue('')
        ], $tableOptions);

        // creates index for column `list_id`
        $this->createIndex(
            'idx-secret_santa_list_member-list_id',
            '{{%secret_santa_list_member}}',
            'list_id'
        );

        // add foreign key for table `secret_santa_list`
        $this->addForeignKey(
            'fk-secret_santa_list_member-list_id',
            '{{%secret_santa_list_member}}',
            'list_id',
            '{{%secret_santa_list}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-secret_santa_list_member_unique',
            '{{%secret_santa_list_member}}',
            ['list_id', 'email'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();

        $this->dropTable('{{%secret_santa_list_member}}');

        $this->getDb()->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();

    }
}
