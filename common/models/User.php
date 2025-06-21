<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 * @property string $role
 * @property string|null $access_token
 *
 * @property CourseRegistration[] $courseRegistrations
 * @property Course[] $courses
 * @property Document[] $documents
 * @property Grade[] $grades
 */
class User extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ROLE_TEACHER = 'teacher';
    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password_reset_token', 'verification_token', 'access_token'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 10],
            [['role'], 'default', 'value' => 'student'],
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['role'], 'string'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'role' => 'Role',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * Gets query for [[CourseRegistrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class, ['student_id' => 'id']);
    }

    /**
     * Gets query for [[Courses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Documents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Grades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrades()
    {
        return $this->hasMany(Grade::class, ['student_id' => 'id']);
    }


    /**
     * column role ENUM value labels
     * @return string[]
     */
    public static function optsRole()
    {
        return [
            self::ROLE_TEACHER => 'teacher',
            self::ROLE_ADMIN => 'admin',
            self::ROLE_STUDENT => 'student',
        ];
    }

    /**
     * @return string
     */
    public function displayRole()
    {
        return self::optsRole()[$this->role];
    }

    /**
     * @return bool
     */
    public function isRoleTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function setRoleToTeacher()
    {
        $this->role = self::ROLE_TEACHER;
    }

    /**
     * @return bool
     */
    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function setRoleToAdmin()
    {
        $this->role = self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isRoleStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function setRoleToStudent()
    {
        $this->role = self::ROLE_STUDENT;
    }
}
