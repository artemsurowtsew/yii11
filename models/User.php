<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $login
 * @property string $password_hash
 * @property string|null $auth_key
 * @property string|null $image
 *
 * @property Article[] $articles
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * Віртуальна властивість для пароля
     */
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'login'], 'required'],
            ['login', 'email'],
            ['login', 'unique'],
            [['name', 'login', 'image'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true],
            [['password'], 'required', 'on' => 'create'], // Вимагається при створенні
            [['password'], 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Ім’я',
            'login' => 'Логін (Email)',
            'password' => 'Пароль',
            'password_hash' => 'Хеш Пароля',
            'auth_key' => 'Auth Key',
            'image' => 'Зображення',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['name', 'login', 'password', 'image'];
        $scenarios['update'] = ['name', 'login', 'password', 'image'];
        return $scenarios;
    }

    /**
     * Генерує хеш пароля
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Генерує auth_key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Реалізуйте token-based автентифікацію, якщо потрібно
        return null;
    }

    /**
     * Finds user by login (username).
     *
     * @param string $login
     * @return User|null
     */
    public static function findByUsername($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * Validates the password.
     *
     * @param string $password
     * @return bool whether the password is valid
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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
        return $this->auth_key === $authKey;
    }

    /**
     * Повертає URL зображення користувача або дефолтне зображення
     * @return string
     */
    public function getImage()
    {
        if ($this->image && file_exists(Yii::getAlias('@webroot/uploads/') . $this->image)) {
            return Yii::getAlias('@web') . '/uploads/' . $this->image;
        }
        return Yii::getAlias('@web') . '/uploads/no-image.png';
    }

    /**
     * Deletes the user's image.
     */
    public function deleteImage()
    {
        if ($this->image && file_exists(Yii::getAlias('@webroot/uploads/') . $this->image)) {
            unlink(Yii::getAlias('@webroot/uploads/') . $this->image);
        }
    }

    /**
     * Deletes the user and their image.
     */
    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

    /**
     * Gets query for related Articles.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for related Comments.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    { 
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Створення нового користувача
     * @return bool whether the user was saved successfully
     */
    public function create()
    {
        $this->setPassword($this->password);
        $this->generateAuthKey();
        return $this->save();
    }

    /**
     * Оновлення користувача
     * @return bool whether the user was saved successfully
     */
    public function updateUser()
    {
        if ($this->password) {
            $this->setPassword($this->password);
        }
        return $this->save();
    }

    /**
     * Перевіряє, чи є користувач адміністратором.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->login === 'antony@gmail.com'; 
    }
}
