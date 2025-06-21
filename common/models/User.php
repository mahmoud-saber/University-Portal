<?php

namespace common\models;

use Yii;
use common\models\Grade;
use yii\db\ActiveRecord;
use common\models\Course;
use common\models\Document;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use common\models\CourseRegistration;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $role
 * @property string|null $access_token
 *
 * @property CourseRegistration[] $courseRegistrations
 * @property Course[] $courses
 * @property Document[] $documents
 * @property Grade[] $grades
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_TEACHER = 'teacher';
    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'email','role'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username', 'email', 'password_reset_token', 'access_token'], 'unique'],
            [['role'], 'default', 'value' => self::ROLE_STUDENT],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'role' => 'Role',
            'access_token' => 'Access Token',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function optsRole()
    {
        return [
            self::ROLE_TEACHER => 'Teacher',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_STUDENT => 'Student',
        ];
    }

    public function displayRole()
    {
        return self::optsRole()[$this->role];
    }

    public function isRoleTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isRoleStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function setRoleToTeacher()
    {
        $this->role = self::ROLE_TEACHER;
    }

    public function setRoleToAdmin()
    {
        $this->role = self::ROLE_ADMIN;
    }

    public function setRoleToStudent()
    {
        $this->role = self::ROLE_STUDENT;
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getCourseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class, ['student_id' => 'id']);
    }

    public function getCourses()
    {
        return $this->hasMany(Course::class, ['teacher_id' => 'id']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class, ['user_id' => 'id']);
    }

    public function getGrades()
    {
        return $this->hasMany(Grade::class, ['student_id' => 'id']);
    }
}