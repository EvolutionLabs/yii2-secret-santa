<?php

use yii\db\Migration;

/**
 * Handles adding budget to table `secret_santa_list`.
 */
class m201119_102521_add_budget_column_to_secret_santa_list_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%secret_santa_list}}', 'budget', $this->integer()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%secret_santa_list}}', 'budget');
    }
}
