<?php

use yii\db\Migration;

class m250621_145818_add_role_and_access_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(20)->defaultValue('student'));
        $this->addColumn('{{%user}}', 'access_token', $this->string()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'access_token');
        $this->dropColumn('{{%user}}', 'role');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250621_145818_add_role_and_access_token_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}