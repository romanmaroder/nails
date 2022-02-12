<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use Yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Role;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property int $status
 * @property string $avatar
 * @property-read \yii\db\ActiveQuery $userPhoto
 * @property-read string[] $rolesDropdown
 * @property-read \yii\db\ActiveQuery $certificate
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED  = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE   = 10;


    public const ROLE_ADMIN   = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_MASTER  = 'master';
    public const ROLE_AUTHOR  = 'author';

    public const DEFAULT_IMAGE = '/img/avatar.jpg';


    public $roles;
    public $password;
    public $color;
    public $rate;


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
            [['rate'], 'number','min'=>0,'max'=>100,'message'=>'{attribute} не может быть меньше 0 и  больше 100'],
            ['username', 'required'],
            ['avatar', 'safe'],
            ['description', 'safe'],
            ['birthday', 'safe'],
            ['phone', 'safe'],
            ['address', 'safe'],
            ['email', 'unique'],
            ['password', 'safe'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [
                ['roles'],
                'required',
                'when'       => function ($model) {
                        return $model->roles;

                },
                'whenClient' => "function(attribute,value){
                
                $('#user-roles input:checkbox').click(function(){
                               if($(this).is(':checked')){
                                  $('#user-color').css({'display':'block'});
                                  $('label[for=user-color]').removeClass('d-none');
                                  $('#user-rate').css({'display':'block'});
                                  $('label[for=user-rate]').removeClass('d-none');
                                 return true;
                               }else{
                                  $('#user-color').css({'display':'none'});
                                   $('label[for=user-color]').addClass('d-none');
                                   $('#user-rate').css({'display':'none'});
                                   $('label[for=user-rate]').addClass('d-none');
                                  //return true;
                               }
                              })
      }"
            ]
        ];
    }


    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'username'    => 'Имя',
            'roles'       => 'Роль',
            'status'      => 'Доступ',
            'avatar'      => 'Аватарка',
            'description' => 'Заметки для мастера',
            'birthday'    => 'День рождения',
            'phone'       => 'Телефон',
            'address'     => 'Адрес',
            'rate'        => 'Ставка',
            'color'       => 'Цвет',
            'password'    => 'Пароль',
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
     *
     * @throws \Exception
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
        $this->color = $this->profile['color'];
        $this->rate = $this->profile['rate'];

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
     *
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
     * @throws \yii\base\NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by phone
     *
     * @param string $phone
     *
     * @return User
     */

    public static function findByUserPhone(string $phone): ?User
    {
        /*return User::findOne($phone);*/
        return static::find()
            ->where(['phone' => $phone])
            ->one();
    }


    /**
     * Finds user by phone
     *
     * @param string $name
     * @param string $phone
     *
     * @return User|false
     */
    public static function findByUserNameAndPhone(string $name, string $phone)
    {
        $users = User::find()->where(['LIKE', 'username', ':param', [':param' => $name . '%']])
            ->andFilterWhere(['phone' => $phone])
            ->asArray()
            ->all();

        foreach ($users as $user) {
            return $user;
        }
        return false;
    }


    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?User
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
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
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
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
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
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws \yii\base\Exception
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
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     *
     * @throws \yii\base\Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
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
                self::ROLE_AUTHOR  => 'Автор',
            ];
        }
        return [
            self::ROLE_MANAGER => 'Менеджер',
            self::ROLE_MASTER  => 'Мастер',
            self::ROLE_AUTHOR  => 'Автор',
        ];
    }


    /**
     * Getting user statuses
     *
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
                return 'запрещен';
        }
    }


    /**
     * Getting a list of clients with the user role
     *
     * @return array
     */
    public static function getClientList(): array
    {
        $clientIds = Yii::$app->authManager->getUserIdsByRole('user');
        $clients   = User::find()->where(['id' => $clientIds])->orderBy(['username' => SORT_ASC])->asArray()->all();
        return ArrayHelper::map($clients, 'id', 'username');
    }


    /**
     * * Getting a list of masters with the master role
     *
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
     *
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
     *
     * @return bool|int|string|null
     */
    public static function getUserTotalCount()
    {
        $client = User::getDb()->cache(
            function () {
                $clientIds = Yii::$app->authManager->getUserIdsByRole('user');
                return User::find()->where(['id' => $clientIds])->count();
            },
            3600
        );

        return $client;
    }


    /**
     * @param $id
     * Count master certificate from table[[Certificate]]
     *
     * @return bool|int|string|null
     */
    public function getCountCertificate($id)
    {
        return Certificate::find()->where(['user_id' => $id])->count();
    }


    public function getCountWorkMaster($id)
    {
        return Photo::find()->where(['user_id' => $id])->count();
    }

    /**
     * Get profile picture
     *
     * @return string
     */
    public function getPicture(): string
    {
        if ($this->avatar && Yii::$app->storage->checkFileExist($this->avatar)) {
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
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['client_id' => 'id']);
    }

    /**
     * Relationship with [[Certificate]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificate(): ActiveQuery
    {
        return $this->hasMany(Certificate::class, ['user_id' => 'id']);
    }


    /**
     * Relationship with [[Profile]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(Profile::class, ['user_id' => 'id']);
    }
    /**
     * Relationship with [[ServiceUser]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRate(): ActiveQuery
    {
        return $this->hasMany(ServiceUser::class, ['user_id' => 'id']);
    }


    /**
     * Return client list dataProvider
     * @return \yii\data\ActiveDataProvider
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDataProvider(): ActiveDataProvider
    {
        if (Yii::$app->user->can('admin')) {
            $query = User::find();
        } else {
            $query = User::find()->where(['!=', 'id', '1']);
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
            ]
        );
        $dependency   = \Yii::createObject(
            [
                'class'    => 'yii\caching\DbDependency',
                'sql'      => 'SELECT MAX(updated_at) FROM user',
                'reusable' => true
            ]
        );
        Yii::$app->db->cache(
            function () use ($dataProvider) {
                $dataProvider->prepare();
            },
            3600,
            $dependency
        );

        return $dataProvider;
    }
}