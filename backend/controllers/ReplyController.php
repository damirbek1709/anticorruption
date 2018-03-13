<?php

namespace backend\controllers;

use Yii;
use frontend\models\Reply;
use frontend\models\ReplySearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;

class ReplyController extends \yii\web\Controller
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
                        'actions' => ['index', 'create', 'delete', 'update','view'],
                        'roles' => ['admin'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['status'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ReplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('@frontend/views/reply/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Reply();
        if ($model->load(Yii::$app->request->post())) {
            $mail = $_POST['Reply']['email'];
            \Yii::$app->mailer->compose(['html'=>'layouts/html'], ['subject' => $model->title, 'content' => $model->text])
                ->setTo($mail)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($model->title)
                ->send();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('@frontend/views/reply/create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('@frontend/views/reply/update', [
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

    protected function findModel($id)
    {
        if (($model = Reply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('@frontend/views/reply/view', [
            'model' => $model
        ]);
    }

}
