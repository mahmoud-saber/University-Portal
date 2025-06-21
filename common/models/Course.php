<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property int $id
 * @property string $title
 * @property string $code
 * @property string|null $description
 * @property int $teacher_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property CourseRegistration[] $courseRegistrations
 * @property Grade[] $grades
 * @property User $teacher
 */
class Course extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['title', 'code', 'teacher_id'], 'required'],
            [['description'], 'string'],
            [['teacher_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 20],
            [['code'], 'unique'],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'code' => 'Code',
            'description' => 'Description',
            'teacher_id' => 'Teacher ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Grades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrades()
    {
        return $this->hasMany(Grade::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(User::class, ['id' => 'teacher_id']);
    }

}
