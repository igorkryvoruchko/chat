<?php

namespace app\controllers;

use app\models\ImageUpload;
use app\models\Message;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup', 'chat'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'chat'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->redirect(['site/chat']);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays chat page.
     *
     * @return string
     */
    public function actionChat()
    {
        $users = User::find()->select(['id', 'username'])->all();

        return $this->render('chat',[
            'users' => $users
        ]);
    }

    /**
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function actionMessageHistory()
    {
        if ( Yii::$app->request->isAjax ) {
            $messages = Message::find()->where(['from' => Yii::$app->request->get('from'), 'to' => Yii::$app->request->get('to')])
                ->orWhere(['to' => Yii::$app->request->get('from'), 'from' => Yii::$app->request->get('to')])
                ->limit(20)->asArray()->all();
            return json_encode($messages);
        }
        return false;
    }

    public function actionSaveFile()
    {
        if($_FILES) {
            $image = new ImageUpload(); // save images
            $files = [];
            foreach ($_FILES as $key => $value) {
                if (UploadedFile::getInstanceByName($key)) { // сохраняем изображение
                    $image->imageFile = UploadedFile::getInstanceByName($key);
                    $fileName = $image->upload();
                    array_push($files, $fileName);
                }
            }
        }
        return json_encode($files);
    }

    public function actionEditMessage(){
        if(Yii::$app->request->isAjax){
            $message = Message::findOne(Yii::$app->request->post('id'));
            $message->message = Yii::$app->request->post('text');
            $message->save();
            return json_encode(["message" => $message->message, "id" => $message->id]);
        }
        return false;
    }

    public function actionDeleteMessage(){
        if(Yii::$app->request->isAjax){
            $message = Message::findOne(Yii::$app->request->post('id'));
            $message->delete();
            return $message->id;
        }
        return false;
    }
}
