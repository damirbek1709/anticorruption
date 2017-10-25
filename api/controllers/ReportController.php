<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Report;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class ReportController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Report';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']); //use my own below

        return $actions;
    }


    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $text='';$user_id=""; $authority_id="";$category_id="";
        $type_id="";$city_id="";$anonymous="";
        if(isset($request['text'])){
            $text=$request['text'];
        }
        if(isset($request['user_id'])){
            $user_id=$request['user_id'];
        }
        if(isset($request['authority_id'])){
            $authority_id=$request['authority_id'];
        }
        if(isset($request['category_id'])){
            $category_id=$request['category_id'];
        }
        if(isset($request['type_id'])){
            $type_id=$request['type_id'];
        }
        if(isset($request['city_id'])){
            $city_id=$request['city_id'];
        }
        if(isset($request['anonymous'])){
            $anonymous=$request['anonymous'];
        }
        $query =Report::find();


        //$query->filterWhere(['incident_verified'=>$verified]);
        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);
        $query->andFilterWhere(['user_id'=> $user_id]);
        $query->andFilterWhere(['authority_id'=> $authority_id]);
        $query->andFilterWhere(['category_id'=> $category_id]);
        $query->andFilterWhere(['type_id'=> $type_id]);
        $query->andFilterWhere(['city_id'=> $city_id]);
        $query->andFilterWhere(['anonymous'=> $anonymous]);

        /*if($ctg){
            $query->joinWith([
                'incidentCategories' => function ($query) use($ctg) {
                    $query->andFilterWhere(['in','category_id', $ctg]);
                }]);

        }*/

        //$query->orderBy(['omapincident.id' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }

    public function actionView($id)
    {
        $model = Report::find()->where(['id'=>$id])->with('authority', 'department', 'city', 'comments','type')->asArray()->one();
        $count=$model['views']+1;
        Yii::$app->db->createCommand("UPDATE report SET views='{$count}' WHERE id='{$id}'")->execute();
        //$model->updateCounters(['views' => 1]);
        /*unset($model['form_id'],$model['location_id']);*/

        //images
        $alias=Yii::getAlias("@frontend");
        $dir=$alias."/web/images/report/".$id;
        $imgs=[];
        if(is_dir($dir)){
            $imgs=scandir($dir);
            foreach($imgs as $k=>$img){
                if(in_array($img,['.','..'])){
                    unset($imgs[$k]);
                }
                elseif(strpos($img,'thumbs')!==false){
                    unset($imgs[$k]);
                }
            }
            $imgs = array_values($imgs);
        }
        $model['images']=$imgs;

        return $model;
    }

    //compare maxId on depend table
    public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='report'")->queryOne();
        return $row['last_update'];
    }
}
