<?php

namespace backend\controllers;

use Yii;
use frontend\models\Report;
use frontend\models\Comments;
use frontend\models\ReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        $model->views++;
        $model->save();
        $newcomment = new Comments();
        return $this->render('view', [
            'model' => $model,
            'comment'=>$newcomment,
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
        //depend table holds timestamp of last table modification. it's for api
        $dao=Yii::$app->db;
        $voc=$dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='report'")->queryOne();
        if(!$voc){
            $dao->createCommand()->insert('depend', [
                'table_name'=>'report',
                'last_update' =>time(),
            ])->execute();
        }
        else{
            $dao->createCommand()->update('depend', ['last_update' =>time()], 'table_name="report"')->execute();
        }

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
}
