<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%grade}}`.
 */
class m250621_152304_create_grade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%grade}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->notNull(),
            'course_id' => $this->integer()->notNull(),
            'grade' => $this->string(10),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),

        ]);
        $this->addForeignKey(
            'fk-grade-student_id',
            '{{%grade}}',
            'student_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-grade-course_id',
            '{{%grade}}',
            'course_id',
            '{{%course}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-grades-student_id', '{{%grade}}');
        $this->dropForeignKey('fk-grades-course_id', '{{%grade}}');
        $this->dropTable('{{%grade}}');
    }
}