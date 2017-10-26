<?php

namespace backend\controllers;

use Yii;
use frontend\models\AuthoritySearch;
use frontend\models\Authority;
use yii\data\ActiveDataProvider;
use frontend\models\Comments;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;

class AuthorityController extends \yii\web\Controller
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
                        'actions' => ['view','update','index'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

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
            $this->alterDepend();
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

    private function alterDepend(){
        //depend table holds timestamp of last table modification. it's for api
        $dao=Yii::$app->db;
        $voc=$dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='authority'")->queryOne();
        if(!$voc){
            $dao->createCommand()->insert('depend', [
                'table_name'=>'authority',
                'last_update' =>time(),
            ])->execute();
        }
        else{
            $dao->createCommand()->update('depend', ['last_update' =>time()], 'table_name="authority"')->execute();
        }
    }
}
