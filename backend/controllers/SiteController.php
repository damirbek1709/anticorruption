<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','login', 'index','remove-image'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin;
                        }
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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

    public function actionRemoveImage()
    {
        $controller = $_POST['controller'];
        $id = $_POST['id'];
        $name = $_POST['key'];
        @chmod( Yii::getAlias("@frontend/web/images/{$controller}/{$id}/thumbs/{$name}"), 0777 );
        @chmod( Yii::getAlias("@frontend/web/images/{$controller}/{$id}/{$name}"), 0777 );
        unlink(Yii::getAlias("@frontend/web/images/{$controller}/{$id}/thumbs/{$name}"));
        unlink(Yii::getAlias("@frontend/web/images/$controller/{$id}/{$name}"),0777);
        return '{}';
    }

    /**
     * Login action.
     *
     * @return string
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
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionCity()
    {
        $key = Yii::$app->request->post('city_id');
        $coords = Vocabulary::getCityCoord($key);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $coords;
    }
}
