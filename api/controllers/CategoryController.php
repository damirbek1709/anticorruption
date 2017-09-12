<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Category;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class CategoryController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Category';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        //unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        $select='id,title,parent,image';
        //$query =Category::find()->select($select)->where(['public'=>1])->asArray()->all();
        $items=Category::allItems();

        $depend=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='category'")->queryOne();
        $items['depend']= $depend['last_update'];

        /*$query = (new \yii\db\Query())
            ->select(['product.id', 'product.title','category_id','unit_id','price','image','unit.title AS unit_title'])
            ->from('product')
            ->leftJoin('unit','product.unit_id=unit.id')
            ->all();*/

        return $items;

        /*return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);*/
    }

    //compare maxId on depend table
    public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='category'")->queryOne();
        return $row['last_update'];
    }
}
