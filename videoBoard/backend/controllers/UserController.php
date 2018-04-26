<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use common\models\User;
use backend\util\Token;
use backend\util\Constant;

class UserController extends ActiveController
{
    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
            ],
        ], parent::behaviors());
    }

    public $modelClass = 'common\models\User';

    public function actionIsLogin()
    {
        if ($_COOKIE[Constant::$REMEMBER_TOKEN]) {
            $token = $_COOKIE[Constant::$REMEMBER_TOKEN];
            $user = Token::getTokenValue($token);
            if ($user) {
                return $user;
            }
        }
        return ['code' => "0", 'msg' => "user not login"];
    }

    public function actionLogin()
    {
        $loginModel = Yii::$app->request->post();

        if ($loginModel) {
            $username = $loginModel['username'];
            $password = $loginModel['password'];
            $rememberMe = $loginModel['rememberMe'];
            return User::login($username, $password, $rememberMe);
        }
        return ['code' => "200", 'msg' => "login success"];
    }

    public function actionLogout()
    {
        if (isset($_COOKIE[Constant::$SESSION_TOKEN])) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            if (Token::getTokenValue($token)) {
                Yii::$app->redis->del($token);
                unset($_COOKIE[Constant::$SESSION_TOKEN]);
            }
        }
        if (isset($_COOKIE[Constant::$REMEMBER_TOKEN])) {
            $token = $_COOKIE[Constant::$REMEMBER_TOKEN];
            if (Token::getTokenValue($token)) {
                Yii::$app->redis->del($token);
                unset($_COOKIE[Constant::$REMEMBER_TOKEN]);
            }
        }
    }

    public function actionRegister()
    {
        $registerModel = Yii::$app->request->post();

        if ($registerModel) {
            $username = $registerModel['username'];
            $password = $registerModel['password'];
            if (User::findByUsername($username) != null) {
                return ['code' => "0", 'msg' => "The user name already exists"];
            } else {
                return User::register($username, $password);
             }
        }
        return ['code' => "-1", 'msg' => "Incorrect user name or password input format"];

    }

    public function actionChangepassword()
    {
        $model = Yii::$app->request->post();
        $oldPassword = $model['oldPassword'];
        $password = $model['password'];
        $token = $_COOKIE[Constant::$SESSION_TOKEN];
        $user = Token::getTokenValue($token);

        if ($model) {
            if ($user && md5($oldPassword) == $user->password) {
                return User::changePassword($user->id, $password);
            }
            return ['code' => "0", 'msg' => "old password is incorrect"];
        }
    }

    public function actionIsResport()
    {
        if ($_COOKIE[Constant::$SESSION_TOKEN]) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            $user = Token::getTokenValue($token);
            if ($user->permission == "1") {
                return ['code' => "0", 'msg' => "user has permission"];
            }
        }

        return ['code' => "1", 'msg' => "user has not permission"];
    }
}
