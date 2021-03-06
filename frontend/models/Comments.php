<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

use Yii;
use frontend\models\Report;
use frontend\models\News;
use frontend\models\Authority;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $name
 * @property string $title
 * @property string $date
 * @property string $email
 * @property string $text
 */
class Comments extends \yii\db\ActiveRecord
{
    public $material;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'date', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'date', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return date('Y-m-d H:i:s', strtotime($this->date));
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['date','news_id','category_id','report_id','user_id','status'], 'safe'],
            [['email'], 'email'],
            [['text'], 'string'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    public function getReport(){
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
    }
    public function getNews(){
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
    public function getAuthority(){
        return $this->hasOne(Authority::className(), ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Имя автора'),
            'date' => Yii::t('app', 'Дата'),
            'email' => Yii::t('app', 'E-mail'),
            'text' => Yii::t('app', 'Текст'),
            'material'=>'Материал',
            'status'=>'Статус',
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->date = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    //for api
    public function fields()
    {
        $model_id=0; $model_name=""; $model_title="";
        if($model_id=$this->news_id){$model_name="news";$model_title=$this->news->title;}
        else if($model_id=$this->category_id){$model_name="authority"; $model_title=$this->authority->title;}
        else if($model_id=$this->report_id){$model_name="report"; $model_title=$this->report->title;}
        return [
            'id', 'text', 'date', 'name', 'email',
            'model' => function ($model) use($model_name) {
                return $model_name;
            },
            'model_id' => function ($model) use($model_id) {
                return $model_id;
            },
            'model_title' => function ($model) use($model_title) {
                return $model_title;
            },
            'status'
        ];
    }
}
