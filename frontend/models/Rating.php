<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property integer $authority_id
 * @property integer $user_id
 * @property integer $rating
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authority_id', 'rating'], 'required'],
            [['authority_id', 'rating','user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'authority_id' => Yii::t('app', 'Authority ID'),
            'rating' => Yii::t('app', 'Rating'),
            'user_id' => Yii::t('app', 'User'),
        ];
    }

    public function beforeSave($insert)
    {
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
        return parent::beforeSave($insert);
    }
}
