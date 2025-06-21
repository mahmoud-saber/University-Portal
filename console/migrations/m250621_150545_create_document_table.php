<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document}}`.
 */
class m250621_150545_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'file_path' => $this->string(255)->notNull(),
            'file_type' => $this->string(50),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-document-user_id',
            '{{%document}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-document-user_id', '{{%document}}');
        $this->dropTable('{{%document}}');
    }

}