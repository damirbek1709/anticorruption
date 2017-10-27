<?php

namespace backend\controllers;
use Yii;
use frontend\models\CommentsSearch;
use frontend\models\Comments;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;

class CommentsController extends \yii\web\Controller
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
                        'actions' => ['index','remove','delete','edit'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CommentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@frontend/views/comments/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('@frontend/views/comments/view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@frontend/views/comments/update', [
                'model' => $model,
            ]);
        }
    }

    public function actionRemove(){
        $request = Yii::$app->getRequest();
        $id = $request->post('id');
        $model = Comments::findOne($id);
        $model->delete();
    }

    public function actionEdit(){
        $request = Yii::$app->getRequest();
        $id = $request->post('id');
        $text = $request->post('text');
        $model = Comments::findOne($id);
        $model->text = $text;
        $model->save();
    }

}
