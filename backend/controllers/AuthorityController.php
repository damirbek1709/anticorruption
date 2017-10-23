<?php

namespace backend\controllers;

use Yii;
use frontend\models\AuthoritySearch;
use frontend\models\Authority;
use yii\data\ActiveDataProvider;
use frontend\models\Comments;

class AuthorityController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new AuthoritySearch();

        $dataProvider = new ActiveDataProvider([
            'query' => AuthoritySearch::find()->where('category_id!=0'),
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

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

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $newcomment = new Comments();
        return $this->render('@frontend/views/authority/view', [
            'model' => $model,
            'comment'=>$newcomment,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Authority::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
