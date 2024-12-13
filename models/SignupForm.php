<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * SignupForm is the model behind the signup form.
 *
 * @property-read User|null $user
 *
 */
class SignupForm extends Model
{
    public $name;
    public $login;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'login', 'password'], 'required'],
            ['login', 'email'],
            ['login', 'unique', 'targetClass' => 'app\models\User', 'targetAttribute' => 'login'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->login = $this->login;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
