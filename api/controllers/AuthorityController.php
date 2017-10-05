<?php

namespace api\controllers;

use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Authority;
use frontend\models\Rating;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class AuthorityController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Authority';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['index', 'view', 'depend'],
        ];
        /*$behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => [
                        'options','login'
                    ],
                ],
                [
                    'allow' => true,
                    'roles' => [
                        'admin',
                    ],
                ],
            ],
        ];*/
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        //$select='id,title,parent,image';
        $query =Authority::find()->asArray()->all();
        /*$query =Authority::find()->all();
        $row=[];
        $titles=[];
        foreach($query as $q){
            $titles[$q->category_id][$q->id]=$q->title;

            if($q->category_id==0){
                
            }
        }*/
        return $query;
    }


    public function actionView($id)
    {
        $model = Authority::find()->where(['id'=>$id])->with('comments')->asArray()->one();
        $model['rating']=Authority::getRating($id);
        /*unset($model['form_id'],$model['location_id']);*/
        return $model;
    }

    public function actionRate()
    {
        if($user_id=Yii::$app->user->id){
            $request = Yii::$app->getRequest();
            $id =  $request->post('id');
            $value =  $request->post('value');
            $value=$value*2;

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
            $query = (new Query())->from('rating')->where(['authority_id'=>$id]);
            if($query->count()==0)
                $rating = 0;
            else
                $rating = round($query->sum('rating') / $query->count());
            return ['id'=>$model->id,'new_rating'=>$rating];
        }
        else{
            return 0;
        }
    }

    public function actionUserrate()
    {
        if($user_id=Yii::$app->user->id){
            $request = Yii::$app->getRequest();
            $id =  $request->get('authority_id');

            $model=Rating::find()->where(['user_id'=>$user_id, 'authority_id'=>$id])->one();
            if($model){
                return ['id'=>$model->id,'rate'=>$model->rating];
            }
        }
        return 0;
    }

    //compare maxId on depend table
    public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='authority'")->queryOne();
        return (int)$row['last_update'];
    }
}
