<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Report;
use frontend\models\Comments;
use frontend\models\ReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\db\Query;
use yii\data\ArrayDataProvider;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{

    public $enableCsrfValidation;

    /**
     * @inheritdoc
     */
    function behaviors()
    {
        return [
            'roleAccess' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'get-locations', 'authority', 'sector', 'city', 'type'],
                        'roles' => ['?', '@', 'admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['?', '@', 'admin']
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['?', '@'],
                       /* 'matchCallback' => function ($rule, $action) {
                            if ($this->isApproved()) {
                                return true;
                            }
                            return false;
                        }*/
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                        /*'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || $this->isUserAuthor()) {
                                return true;
                            }
                            return false;
                        }*/
                    ],

                    [
                        'allow' => true,
                        'actions' => ['update', 'delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || $this->isUserAuthor()) {
                                return true;
                            }
                            return false;
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['admin'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || $this->isUserAuthor() || $this->isApproved()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = $this->deviceCheck();
        $searchModel = new ReportSearch();
        $dataProvider = new ActiveDataProvider([
            //'query' => ReportSearch::find()->where(['status' => 1]),
            'query' => ReportSearch::find(),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSector($id)
    {
        $searchModel = new ReportSearch();
        $this->layout = $this->deviceCheck();
        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['category_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function deviceCheck()
    {
        $layout = "main";
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            $layout = "mobile-main";
        }
        return $layout;
    }

    public function actionCity($id)
    {
        $searchModel = new ReportSearch();
        $this->layout = $this->deviceCheck();
        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['city_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionType($id)
    {
        $searchModel = new ReportSearch();
        $this->layout = $this->deviceCheck();
        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['type_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAuthority($id)
    {
        $searchModel = new ReportSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['authority_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = $this->deviceCheck();
        $model = $this->findModel($id);
        $model->updateCounters(['views' => 1]);
        $newcomment = new Comments();
        return $this->render('view', [
            'model' => $model,
            'comment' => $newcomment,
        ]);
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Report();
        //$this->layout = $this->deviceCheck();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //$msg = Yii::$app->db->createCommand("SELECT `value` FROM vocabulary WHERE `key`='lookup_submitted'")->queryOne();
            //Yii::$app->getSession()->setFlash('success', $msg['value']);
            /*if (Yii::$app->user->isGuest) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }*/
            return $this->redirect(['view', 'id' => $model->id]);

        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Report model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Report model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public
    function actionGetLocations()
    {
        if ($authority = Yii::$app->request->get('authority')) {
            $auth_query = " AND authority_id='{$authority}'";
        } else {
            $auth_query = "";
        }

        if ($sector = Yii::$app->request->get('sector')) {
            $sector_query = " AND category_id='{$sector}'";
        } else {
            $sector_query = "";
        }

        if ($city = Yii::$app->request->get('city')) {
            $city_query = " AND city_id='{$city}'";
        } else {
            $city_query = "";
        }

        if ($type = Yii::$app->request->get('type')) {
            $type_query = " AND type_id='{$type}'";
        } else {
            $type_query = "";
        }

        $rows = Yii::$app->db->createCommand("SELECT id, title, lat, lon FROM report WHERE lat<>0 AND lon<>0" . $auth_query . $sector_query . $city_query . $type_query)->queryAll();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $rows;
    }


    protected
    function isUserAuthor()
    {
        return $this->findModel(Yii::$app->request->get('id'))->user_id == Yii::$app->user->id;
    }

    protected
    function isApproved()
    {
        return $this->findModel(Yii::$app->request->get('id'))->status == 1;
    }
}
