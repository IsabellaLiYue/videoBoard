<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\util\Token;
use backend\util\Constant;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $is_deleted
 * @property integer $permission
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $weibo_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    const IS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            ['is_deleted', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_deleted', 'in', 'range' => [self::STATUS_ACTIVE, self::IS_DELETED]],
        ];
    }

    public static function changePassword($id, $password)
    {
        $user = static::findIdentity($id);
        $user->password = md5($password);
        $user['updated_at'] = date("Y-m-d H:i:s", time() + 8 * 3600);
        $user->save();
        return ['code' => "200", 'msg' => "change password success"];
    }

    public static function register($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->password = md5($password);
        $user->created_at = date("Y-m-d H:i:s", time() + 8 * 3600);
        $user->updated_at = date("Y-m-d H:i:s", time() + 8 * 3600);
        $user->save();
        return ['code' => "200", 'msg' => "user register success"];
    }

    public static function login($username, $password ,$remremberMe)
    {
        if ($username == "" || $password == "") {
            return ['code' => "-1", 'msg' => "username and password are required"];
        } else {
            $user = User::findByUsername($username);
            if ($user && $user->password == md5($password)) {
                $userId = $user->id;
                Token::putToken($user, 1800, Constant::$SESSION_TOKEN);
                if ($remremberMe) {
                    Token::putToken($user, 1800, Constant::$REMEMBER_TOKEN);
                }
                return $userId;
            }
            return ['code' => "0", 'msg' => "user or password is incorrect"];
        }
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_deleted' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'is_deleted' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'is_deleted' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
