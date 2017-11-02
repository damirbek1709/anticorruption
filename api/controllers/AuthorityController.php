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
            'only' => ['rate','userrate'],
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
        unset($actions['delete'], $actions['create'], $actions['update']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        return Authority::find()->all();
    }


    public function actionView($id)
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        return Authority::find()->where(['id'=>$id])->one();
    }

    public function actionRate()
    {
        if($user_id=Yii::$app->user->id){
            $request = Yii::$app->getRequest();
            $id =  $request->post('id');
            $value = (int) $request->post('value');
            $msg="user found ";

            $model=Rating::find()->where(['user_id'=>$user_id, 'authority_id'=>$id])->one();
            if($model){
                $msg.=" model found ";
                if($model->rating!=$value){
                    $model->rating=$value;
                    $model->save();
                    if($model->hasErrors()){
                        $msg.="upd error";
                    }
                    else{
                        $msg.=" rating updated ";
                    }
                }
            }
            else{
                $model = new Rating();
                $model->rating = $value;
                $model->authority_id = $id;
                $model->user_id = $user_id;
                $model->save();
                if($model->hasErrors()){
                    $msg.="save error ";
                }
                else{
                    $msg.=" rating created ";
                }
            }
            $query = (new Query())->from('rating')->where(['authority_id'=>$id]);
            if($query->count()==0)
            {$rating = 0; $msg.=" count 0 ";}
            else
                $rating = round($query->sum('rating') / $query->count());
            return ['id'=>$model->id,'new_rating'=>$rating, 'msg'=>$msg];
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
