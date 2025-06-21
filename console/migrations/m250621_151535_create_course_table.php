<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%course}}`.
 */
class m250621_151535_create_course_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
            $this->createTable('{{%course}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'code' => $this->string(20)->notNull()->unique(),
            'description' => $this->text(),
            'teacher_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),

        ]);
   
        $this->addForeignKey(
            'fk-courses-teacher_id',
            '{{%course}}',
            'teacher_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-course-teacher_id', '{{%course}}');
        $this->dropTable('{{%course}}'); }

}