<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use Yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property string $color
 * @property int $created_at
 * @property int $updated_at
 * @property-read string $authKey
 * @property-read string $picture
 * @property-read \yii\db\ActiveQuery $userPhoto
 * @property-read string[] $rolesDropdown
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;


    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_MASTER = 'master';

    public const DEFAULT_IMAGE = '/img/avatar.jpg';


    public $roles;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['roles', 'safe'],
            ['color', 'safe'],
            ['username', 'required'],
            ['avatar', 'safe'],
            ['description', 'safe'],
            ['birthday', 'safe'],
            ['phone', 'safe'],
            ['address', 'safe'],

            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'username'    => 'Имя',
            'color'       => 'Цвет',
            'roles'       => 'Роль',
            'status'      => 'Доступ',
            'avatar'      => 'Аватарка',
            'description' => 'Заметки для мастера',
            'birthday'    => 'День рождения',
            'phone'       => 'Телефон',
            'address'     => 'Адрес',
            'created_at'  => 'Создан'
        ];
    }

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveRoles']);
        parent::__construct();
    }

    /**
     * Revoke old roles and assign new if any
     */
    public function saveRoles()
    {
        Yii::$app->authManager->revokeAll($this->getId());

        if (is_array($this->roles)) {
            foreach ($this->roles as $roleName) {
                if ($role = Yii::$app->authManager->getRole($roleName)) {
                    Yii::$app->authManager->assign($role, $this->getId());
                }
            }
        } else {
            $role = Yii::$app->authManager->getRole('user');
            Yii::$app->authManager->assign($role, $this->getId());
        }
    }

    /**
     * Populate roles attribute with data from RBAC after record loaded from DB
     */
    public function afterFind()
    {
        $this->roles = $this->getRoles('name');
    }

    /**
     * Get user roles from RBAC
     *
     * @param $column_name
     *
     * @return array
     */
    public function getRoles($column_name): array
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->getId());
        return ArrayHelper::getColumn($roles, $column_name, true);
    }

    /**
     * Get user role from RBAC
     * @return \yii\rbac\Role
     */
    public static function getRole(): Role
    {
        return array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))[0];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string  $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string  $token  password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status'               => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Finds user by verification email token
     *
     * @param  string  $token  verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne(
            [
                'verification_token' => $token,
                'status'             => self::STATUS_INACTIVE
            ]
        );
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param  string  $token  password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password  password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param  string  $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     *
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString().'_'.time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    /**
     * @return array
     */
    public function getRolesDropdown(): array
    {
        if (Yii::$app->user->can('admin')) {
            return [
                self::ROLE_ADMIN   => 'Админ',
                self::ROLE_MANAGER => 'Менеджер',
                self::ROLE_MASTER  => 'Мастер',
            ];
        }
        return [
            self::ROLE_MANAGER => 'Менеджер',
            self::ROLE_MASTER  => 'Мастер',
        ];
    }


    /**
     * Getting user statuses
     * @return string[]
     */
    public function getStatus(): array
    {
        return [
            self::STATUS_ACTIVE   => 'разрешить',
            self::STATUS_INACTIVE => 'запретить',
//            self::STATUS_DELETED  => 'удален'
        ];
    }

    /**
     * Getting user status
     *
     * @param $status
     *
     * @return string
     */
    public function getStatusUser($status): string
    {
        switch ($status) {
            case  10:
                return '<span class="text-success"><i class="fas fa-universal-access"></i> активный</span>';
            case  9:
                return '<span class="text-danger"><i class="fas fa-universal-access"></i> не активный</span>';
            case  0:
                return 'удален';
            default:
                return  'запрещен';
        }
    }


    /**
     * Getting a list of clients with the user role
     * @return array
     */
    public static function getClientList(): array
    {
        $clientIds = Yii::$app->authManager->getUserIdsByRole('user');
        $clients   = User::find()->where(['id' => $clientIds])->asArray()->all();
        return ArrayHelper::map($clients, 'id', 'username');
    }


    /**
     * * Getting a list of masters with the master role
     * @return array
     */
    public static function getMasterList(): array
    {
        /*$master= User::find()->select('user.*')
            ->leftJoin('auth_assignment', '`auth_assignment`.`user_id` = `user`.`id`')
            ->andWhere(['auth_assignment.item_name'=>'master'])->asArray()->all();*/
        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');
        $master    = User::find()->where(['id' => $masterIds])->asArray()->all();
        return ArrayHelper::map($master, 'id', 'username');
    }

    /**
     * Getting user data
     * @param $userId
     *
     * @return array|\common\models\User|\yii\db\ActiveRecord|null
     */
    public static function getUserInfo($userId)
    {
        return User::find()
            ->select(['username', 'avatar', 'description', 'address', 'birthday', 'phone'])
            ->where(['id' => $userId])
            ->one();
    }

    /**
     * Number of clients
     * @return bool|int|string|null
     */
    public static function getUserTotalCount()
    {
        $clientIds = Yii::$app->authManager->getUserIdsByRole('user');
        return User::find()->where(['id'=>$clientIds])->count();
    }


    /**
     * @param $id
     * Count master certificate from table[[Certificate]]
     * @return bool|int|string|null
     */
    public function getCountCertificate($id){
        return Certificate::find()->where(['user_id'=>$id])->count();
    }


    public function getCountWorkMaster($id) {
        return Photo::find()->where(['user_id'=>$id])->count();
    }

    /**
     * Get profile picture
     *
     * @return string
     */
    public function getPicture(): string
    {
        if ($this->avatar) {
            return Yii::$app->storage->getFile($this->avatar);
        }
        return self::DEFAULT_IMAGE;
    }

    /**
     * Delete picture from user record and file system
     *
     * @return bool
     */

    public function deletePicture(): bool

    {
        if ($this->avatar && Yii::$app->storage->deleteFile($this->avatar)) {
            $this->avatar = null;

            return $this->save(false, ['avatar']);
        }

        return false;
    }

    /**
     * Relationship with [[Photo]] table
     * @return \yii\db\ActiveQuery
     */
    public function getUserPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['client_id' => 'id']);
    }

    /**
     * Relationship with [[Certificate]] table
     * @return \yii\db\ActiveQuery
     */
    public function getCertificate(): ActiveQuery
    {
        return $this->hasMany(Certificate::class, ['user_id'=>'id']);
    }
}

