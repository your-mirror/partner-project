<?php
namespace app\models;

use Yii;
use yii\log\Logger;
use dektrium\user\helpers\Password;

class Manager extends User
{
    public function create($redactor_id)
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->confirmed_at = time();

        if ($this->password == null) {
            $this->password = Password::generate(8);
        }

        if ($this->username === null) {
            $this->generateUsername();
        }

        $this->role = 'manager';

        $this->trigger(self::USER_CREATE_INIT);

        if ($this->save()) {
            $this->trigger(self::USER_CREATE_DONE);

            //set rbac role
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('manager');
            $auth->assign($authorRole, $this->getId());

            $this->mailer->sendWelcomeMessage($this);
            \Yii::getLogger()->log('User has been created', Logger::LEVEL_INFO);
            return true;
        }

        \Yii::getLogger()->log('An error occurred while creating user account', Logger::LEVEL_ERROR);

        return false;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'journalist_id']);
    }
}