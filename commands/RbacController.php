<?php
namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit($userName)
    {
        // получаем юзера по его username
        $user = User::find()->where(['username'=>$userName])->one();
        $auth = \Yii::$app->authManager;
        // добавляем разрешение "createUser"
        $createUser = $auth->createPermission('admin_permission');
        $createUser->description = 'Admin';
        $auth->add($createUser);
        // добавляем роль "admin" и даём роли разрешение "admin_permission"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createUser);
        // Назначение ролей пользователям. по их id IdentityInterface::getId()
        $auth->assign($admin, $user->id);
        // запускаем в консоли:    php yii rbac/init ivan        (где ivan = username)
    }
}
