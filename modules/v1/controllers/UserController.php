<?php
/**
 * Project: serverlist-api
 * User: Dominik
 * Date: 27.02.2019
 * Time: 23:33
 */

namespace app\modules\v1\controllers;


use app\components\ApiException;
use app\controllers\ApiController;
use app\models\LoginToken;
use app\models\User;
use app\models\UserNotification;
use app\modules\v1\models\Server;
use Firebase\JWT\JWT;
use yii\data\ActiveDataProvider;
use yii\filters\Cors;
use yii\web\Response;
use yii\filters\VerbFilter;

class UserController extends ApiController
{

    public $modelClass = 'app\models\User';


    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                // restrict access to domains:
                'Origin' => static::allowedDomains(),
                'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Request-Headers' => ['x-requested-with', 'content-type'],
                'Access-Control-Max-Age' => 0, // Cache (seconds)
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];
        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    public function actionRegister()
    {
        $user = new User();
        $user->attributes = \Yii::$app->request->post('user');

        if ($user->validate()) {
            $user->save();
            return $user->generateLoginToken();
        } else {
            throw new ApiException(400, $user->errors);
        }
    }

    public function actionLogin()
    {
        $data = \Yii::$app->request->post('user');
        $user = User::findByUsername($data['username']);
        if (!$user) {
            throw new ApiException(401, 'Username not found');
        }

        if (!$user->validatePassword($data['password'])) {
            throw new ApiException(401, 'Incorrect username or password.');
        }

        $loginToken = new LoginToken();
        $loginToken->user_id = $user->id;
        if ($loginToken->save())
            return $loginToken->getAsJWTToken();
        throw new ApiException(401, 'Couldn\'t generate login token.');
    }


    public function actionServer($id)
    {

        $user = $this->validateUser('Server');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');

        $criteria = ['id' => $id];
        $server = Server::findOne($criteria);
        if (!$server)
            throw new ApiException(404, 'Server not found!');

        $server = Server::findOne(array_merge($criteria, ['user_id' => $user]));
        if (!$server)
            throw new ApiException(403, 'Not users server.');

        return true;
    }

    public function actionServers()
    {
        $user = $this->validateUser('Server');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');

        return Server::findAll(['user_id' => $user]);
    }


    public function actionLogout()
    {
        $user = $this->validateUser('User');
        if (!$user)
            throw new ApiException(401, 'User not authorized');

        $this->logout();

        return true;
    }


    private function logout()
    {
        $token = JWT::decode($this->getValidationData(), LoginToken::LOGIN_TOKEN_KEY, array("HS256"));

        $model = User::findAccessToken($this->getValidationMethod(), $token->token);
        if ($model->isExpired())
            throw new ApiException(403, 'Token expired');

        if (!$model->expire())
            throw new ApiException(500, 'Something went wrong at saving tokens');
    }

    public function actionRelogin()
    {
        $user = $this->validateUser('Server');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');

        // log out user -> expire the token
        $this->logout();

        $loginToken = new LoginToken();
        $loginToken->user_id = $user;
        if ($loginToken->save())
            return $loginToken->getAsJWTToken();
        throw new ApiException(401, 'Couldn\'t generate login token.');
    }

    public function actionLogoutall()
    {

        if ($this->getValidationMethod() != User::WEB_LOGIN)
            throw new ApiException(405, 'Not allowed from API requests');

        $user = $this->validateUser('User');
        if (!$user)
            throw new ApiException(401, 'User not authorized');

        $tokens = LoginToken::find()->user($user)->active()->all();

        foreach ($tokens as $token) {
            $token->expire();
        }
        return true;
    }

    public function actionNotifications()
    {
        $user = $this->validateUser('UserNotification');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');


        $dataProvider = new ActiveDataProvider([
            'query' => UserNotification::find()
                ->user($user)
                ->orderBy('date DESC'),
            'pagination' => [
                'defaultPageSize' => 5,
                'pageSize' => 5, //to set count items on one page, if not set will be set from defaultPageSize
            ]
        ]);


        return $dataProvider;
    }

    public function actionNotification($id)
    {

        $user = $this->validateUser('UserNotification');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');


        $notification = UserNotification::find()->user($user)->andWhere(['id' => $id])->one();
        if (!$notification)
            throw new ApiException(403, 'Not your notification.');

        $notification->read = 1;
        if ($notification->save())
            return $notification;
        return $notification->errors;
    }

    public function actionUpdate()
    {

        $user = $this->validateUser('Server');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');

        $user = User::findById($user);
        $user->attributes = \Yii::$app->request->post('user');
        if (!$user->validate()) {
            throw new ApiException(400, $user->errors);
        }

        $user->save();
        return $user;
    }

    public function actionAccount()
    {

        $user = $this->validateUser('Server');
        if (!$user)
            throw new ApiException(401, 'User not authorized.');

        $user = User::findById($user);
        return $user;
    }

}