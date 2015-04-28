<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class GroupRule extends Rule {

    public $name = 'userGroup';

    public function execute($user, $item, $params) {
        //check the role from table user
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === 'admin') {
                return $role == $item->name;
            } elseif ($item->name === 'manager') {
                return $role == 'admin' || $role == 'manager';
            } else {
                return false;
            }
        }
        return false;
    }
}