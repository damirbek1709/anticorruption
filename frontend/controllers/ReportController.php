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
                        'actions' => ['index','get-locations','authority','sector','city','type'],
                        'roles' => ['?', '@','admin']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@','admin']
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['?','@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isApproved()) {
                                return true;
                            }
                            return false;
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['view'],
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
                        'actions' => ['update','delete'],
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
                        'actions' => ['update','status'],
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
        $searchModel = new ReportSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['status' => 1]),
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

        $dataProvider = new ActiveDataProvider([
            'query' => ReportSearch::find()->where(['category_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCity($id)
    {
        $searchModel = new ReportSearch();

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $msg = Yii::$app->db->createCommand("SELECT `value` FROM vocabulary WHERE `key`='lookup_submitted'")->queryOne();
            Yii::$app->getSession()->setFlash('success', $msg['value']);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Report model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
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
    public function actionDelete($id)
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
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetLocations()
    {
        $rows = Yii::$app->db->createCommand("SELECT id, title, lat, lon FROM report WHERE lat<>0 AND lon<>0")->queryAll();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $rows;
    }

    public function actionStatus()
    {
        $this->enableCsrfValidation = false;
        $request = Yii::$app->getRequest();
        $id = $request->post('id');
        $status = $request->post('status');
        $model = $this->findModel($id);
        $model->status = $status;
        if ($model->save()) {
            return $model->status;
        }
    }

    protected function isUserAuthor()
    {
        return $this->findModel(Yii::$app->request->get('id'))->user_id == Yii::$app->user->id;
    }

    protected function isApproved()
    {
        return $this->findModel(Yii::$app->request->get('id'))->status == 1;
    }
}
