<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Comments;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class CommentsController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Comments';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        //unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action

        $request=\Yii::$app->request->get();
        $user_id="";

        if(isset($request['user_id'])){
            $user_id=$request['user_id'];
        }
        $query =Comments::find();
        $auth_token=Yii::$app->request->get('auth_key');
        if($auth_token){
            $user=Yii::$app->db->createCommand("SELECT id FROM `user` WHERE auth_key='{$auth_token}'")->queryOne();
        }
        if(!empty($user['id']) && $user['id']==$user_id){
            $query->where(['user_id'=>$user['id']]);
        }
        else{
            $query->where(['status'=>1]);
            $query->andFilterWhere(['user_id'=> $user_id]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }


    //compare maxId on depend table
    /*public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='category'")->queryOne();
        return $row['last_update'];
    }*/
}
