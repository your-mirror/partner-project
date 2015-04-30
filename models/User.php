<?php
namespace app\models;

use app\modules\site\controllers\StatisticController;
use Yii;

use dektrium\user\models\User as BaseUser;
use dektrium\user\models\Token;
use dektrium\user\helpers\Password;
use yii\log\Logger;

class User extends BaseUser
{
    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        if ($this->module->enableConfirmation == false) {
            $this->confirmed_at = time();
        }

        if ($this->module->enableGeneratingPassword) {
            $this->password = Password::generate(8);
        }


        $this->role = 'manager';

        $this->trigger(self::USER_REGISTER_INIT);

        if ($this->save()) {
            $this->trigger(self::USER_REGISTER_DONE);

            //set rbac role
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('manager');
            $auth->assign($authorRole, $this->getId());

            if ($this->module->enableConfirmation) {
                $token = \Yii::createObject([
                    'class' => Token::className(),
                    'type'  => Token::TYPE_CONFIRMATION,
                ]);
                $token->link('user', $this);
                $this->mailer->sendConfirmationMessage($this, $token);
            } else {
                \Yii::$app->user->login($this);
            }
            if ($this->module->enableGeneratingPassword) {
                $this->mailer->sendWelcomeMessage($this);
            }
            \Yii::$app->session->setFlash('info', $this->getFlashMessage());
            \Yii::getLogger()->log('User has been registered', Logger::LEVEL_INFO);
            return true;
        }

        \Yii::getLogger()->log('An error occurred while registering user account', Logger::LEVEL_ERROR);

        return false;
    }

    public function attributeLabels()
    {
        return [
            'username'          => \Yii::t('user', 'Username'),
            'email'             => \Yii::t('user', 'Email'),
            'registration_ip'   => \Yii::t('user', 'Registration ip'),
            'unconfirmed_email' => \Yii::t('user', 'New email'),
            'password'          => \Yii::t('user', 'Password'),
            'created_at'        => \Yii::t('user', 'Registration time'),
            'confirmed_at'      => \Yii::t('user', 'Confirmation time')
        ];
    }

    public function getSites()
    {
        return $this->hasMany(Sites::className(), ['author_id' => 'id']);
    }
}