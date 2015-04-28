<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\rbac\DbManager;

class RbacController extends Controller {

    public function actionInit()
    {
        $auth = new DbManager;
        $auth->init();
        $auth->removeAll();

        //Добавляем правила
            $groupRule = new \app\rbac\GroupRule();
            $auth->add($groupRule);
            $authorRule = new \app\rbac\AuthorRule();
            $auth->add($authorRule);

        //Добавляем пользователей и группу к ним
            $manager = $auth->createRole('manager');
            $manager->description = 'Manager';
            $manager->ruleName = $groupRule->name;
            $auth->add($manager);

            $admin  = $auth->createRole('admin');
            $admin->description = 'Administrator';
            $admin->ruleName = $groupRule->name;
            $auth->add($admin);

        //Добавляем разрешения
            //Сайты
            $crudSite  = $auth->createPermission('crudSite');
            $crudSite->description = 'Управление сайтом';
            $auth->add($crudSite);

        //Добавляем правила для доступов
            //Правила для сайтов
            $crudOwnSite = $auth->createPermission('crudOwnSite');
            $crudOwnSite->description = 'Управление своим сайтом';
            $crudOwnSite->ruleName = $authorRule->name;
            $auth->add($crudOwnSite);


        //Дерево наследований
        $auth->addChild($manager, $crudOwnSite);

        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $crudSite);
    }
}