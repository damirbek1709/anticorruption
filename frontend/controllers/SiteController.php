<?php

namespace frontend\controllers;

use frontend\models\Rating;
use frontend\models\Vocabulary;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\helpers\FileHelper;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['index', 'login', 'map', 'city','remove-image'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'image-upload', 'rating', 'city', 'map', 'remove-image'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://anticorruption.kg/images/uploads', // Directory URL address, where files are stored.
                'path' => '@webroot/images/uploads'
            ],
            'file-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => 'http://anticorruption.kg/files/uploads', // Directory URL address, where files are stored.
                'path' => '@webroot/files/uploads',
                'uploadOnlyImage' => false, // For not image-only uploading.
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMap()
    {
        return $this->render('map');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */

    public function actionRemoveImage()
    {
        $controller = $_POST['controller'];
        $id = $_POST['id'];
        $name = $_POST['key'];

        unlink(Yii::getAlias("@webroot/images/{$controller}/{$id}/thumbs/{$name}"));
        unlink(Yii::getAlias("@webroot/images/{$controller}/{$id}/{$name}"));
        return '{}';
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public function actionRating()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        } else {
            $user_id = Yii::$app->user->id;
            $request = Yii::$app->getRequest();
            $id =  $request->post('id');
            $value =  $request->post('value');

            $model=Rating::find()->where(['user_id'=>$user_id, 'authority_id'=>$id])->one();
            if($model){
                if($model->rating!=$value){
                    $model->rating=$value;
                    $model->save();
                }
            }
            else{
                $model = new Rating();
                $model->rating = $value;
                $model->authority_id = $id;
                $model->user_id = $user_id;
                $model->save();
            }
            return true;
        }
    }

    //kartik fileupload

    public function actionImgDelete($id, $model_name)
    {
        $key = Yii::$app->request->post('key');
        $webroot = Yii::getAlias('@webroot');
        if (is_dir($dir = $webroot . "/images/{$model_name}/" . $id)) {
            if (is_file($dir . '/' . $key)) {
                $expl = explode('s_', $key);
                $full = $expl[1];
                @unlink($dir . '/' . $key);
                @unlink($dir . '/' . $full);
                Yii::$app->db->createCommand("UPDATE {$model_name} SET text='{$key}' WHERE id='{$id}'")->execute();
            }
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return true;
    }


    public function actionCity()
    {
        $key = Yii::$app->request->post('city_id');
        $coords = Vocabulary::getCityCoord($key);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $coords;
    }
}
