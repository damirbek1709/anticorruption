<?php

namespace backend\controllers;
use Yii;
use frontend\models\PoliticsSearch;
use frontend\models\Politics;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use yii\data\ActiveDataProvider;
use frontend\models\Vocabulary;
class PoliticsController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['category','delete','update','create','view'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        return $this->render('@frontend/views/politics/view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCategory($id)
    {
        $searchModel = new PoliticsSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => PoliticsSearch::find()->where(['category_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);
        $title = Vocabulary::find()->where(['id'=>$id,'key'=>'politics_category'])->select('value')->scalar();

        return $this->render('@frontend/views/politics/list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title'=>$title,
        ]);
    }

    public function actionCreate()
    {
        $model = new Politics();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@frontend/views/politics/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Document model.
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
            return $this->render('@frontend/views/politics/update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Politics::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
