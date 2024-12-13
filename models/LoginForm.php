<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    /**
     * @var User|null Кешований користувач
     */
    private $_user = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Валідатор пароля.
     *
     * @param string $attribute Анотація атрибута
     * @param array $params Додаткові параметри
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Некоректний логін або пароль.');
            }
        }
    }

    /**
     * Логін користувача.
     *
     * @return bool Повертає true, якщо логін успішний
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user instanceof \yii\web\IdentityInterface) {
                return Yii::$app->user->login($user, 
                    $this->rememberMe ? 3600*24*30 : 0);
            }
        }
        return false;
    }

    /**
     * Знаходить користувача за логіном.
     *
     * @return User|null Користувач або null, якщо не знайдено
     */
    protected function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->login);
        }

        return $this->_user;
    }
}
