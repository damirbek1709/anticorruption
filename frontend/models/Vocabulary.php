<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "vocabulary".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $ordered_id
 * @property integer $parent
 */
class Vocabulary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vocabulary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'ordered_id', 'parent'], 'required'],
            [['ordered_id', 'parent'], 'integer'],
            [['key'], 'string', 'max' => 255],
            [['value','value_ky','value_en'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
            'ordered_id' => Yii::t('app', 'Ordered ID'),
            'parent' => Yii::t('app', 'Parent'),
        ];
    }
    
    public static function getCityCoord($city_id){
        $myLocation=[42.8742589,74.6131682]; //bishkek by default
        switch($city_id){
            case 1: $myLocation = [42.8742589,74.6131682];//bishkek
                break;
            case 16: $myLocation = [40.5169,72.8056];//Osh
                break;
            case 17: $myLocation = [40.936590,72.983248];//jalalabad
                break;
            case 18: $myLocation = [42.4892,78.3976];//karakol
                break;
            case 19: $myLocation = [41.4282,75.9938];//naryn
                break;
            case 20: $myLocation = [40.0590,70.8211];//batken
                break;
            case 21: $myLocation = [42.5204,72.2505];//talas
                break;
            case 22: $myLocation = [42.8385,75.2983];//tokmok
                break;
            case 23: $myLocation = [40.7703,73.2990];//uzgen
                break;
            case 24: $myLocation = [42.8022,73.8500];//karabalta
                break;
            case 25: $myLocation = [42.46017, 76.18709];//balykchy
                break;
            case 26: $myLocation = [42.8908,74.8510];//kant
                break;
            case 30: $myLocation = [42.8236, 73.7347];//panfilov region (kaindy)
                break;
            case 31: $myLocation = [42.8093, 73.905];//jayil region (karabalta)
                break;
            case 32: $myLocation = [42.8261, 74.1714];//moscow region (belovodskoe)
                break;
            case 33: $myLocation = [42.8528, 74.3548];//sokuluk region (sokuluk)
                break;
            case 34: $myLocation = [42.8727, 74.7348];//alamedin region (lebedinovka)
                break;
            case 36: $myLocation = [42.8385,75.2983];//chuy region (tokmok)
                break;
            case 37: $myLocation = [42.876, 74.9048];//ysykata region (kant)
                break;
            case 38: $myLocation = [42.7795, 75.7459];//kemin region (kemin)
                break;
            case 40: $myLocation = [40.3091, 73.4882];//alai region (gulcha)
                break;
            case 41: $myLocation = [40.5075, 72.5541];//aravan region (aravan)
                break;
            case 42: $myLocation = [40.6333, 75.5880];//karakulja region (karakulja)
                break;
            case 43: $myLocation = [40.6411, 72.9379];//karasuu region (karasuu)
                break;
            case 44: $myLocation = [40.2591, 72.655];//nookat region (nookat)
                break;
            case 45: $myLocation = [40.7592, 73.355];//uzgen region (uzgen)
                break;
            case 46: $myLocation = [39.5446, 72.2574];//chon-alai region (daroot-korgon)
                break;
            case 48: $myLocation = [42.4939, 78.5777];//ak-suu region (ak-suu)
                break;
            case 49: $myLocation = [42.3416,78.0040];//jeti-oguz region (kyzyl-suu)
                break;
            case 50: $myLocation = [42.1026, 77.0451];//ton region (bokonbai)
                break;
            case 51: $myLocation = [42.7263, 78.3875];//tup region (tup)
                break;
            case 52: $myLocation = [42.6428, 77.1381];//ik region (cholpon-ata)
                break;
            case 54: $myLocation = [41.4584, 71.7448];//aksy region (kerben)
                break;
            case 55: $myLocation = [41.3912, 71.5423];//alabuka region (alabuka)
                break;
            case 56: $myLocation = [41.0348, 72.6505];//bazarkurgan region (bazarkurgan)
                break;
            case 57: $myLocation = [41.0386,72.4817];//nooken region (kochkor-ata instead of massy)
                break;
            case 58: $myLocation = [40.8943, 72.9322];//suzak region (suzak)
                break;
            case 59: $myLocation = [41.4098, 74.3665];//toguz-toro region (kazarman)
                break;
            case 60: $myLocation = [41.8520, 72.9264];//toktogul region (toktogul)
                break;
            case 61: $myLocation = [41.7431, 71.1543];//chatkal region (kanysh-kiya)
                break;
            case 63: $myLocation = [41.2564, 75.0092];//aktala region (baetovo)
                break;
            case 64: $myLocation = [41.1626, 75.8551];//atbashy region (atbashy)
                break;
            case 65: $myLocation = [41.9227, 74.5649];//jumgal region (chaek)
                break;
            case 66: $myLocation = [42.2227, 75.805];//kochkor region (kochkorka)
                break;
            case 67: $myLocation = [41.4257, 76.0549];//naryn region (naryn)
                break;
            case 69: $myLocation = [42.481, 71.9845];//bakaiata region (bakaiata)
                break;
            case 70: $myLocation = [42.6125, 71.6573];//kara-buura region (kyzyl-adyr)
                break;
            case 71: $myLocation = [42.698, 71.647];//manas region (pokrovka)
                break;
            case 72: $myLocation = [42.4811, 72.5463];//talas region (manas)
                break;
            case 74: $myLocation = [40.0423, 70.8718];//batken region (batken)
                break;
            case 75: $myLocation = [40.128216, 71.723767];//kadamjai region (kadamjai instead of pulgon)
                break;
            case 76: $myLocation = [39.8257, 69.5716];//leilek region (isfana)
                break;
        }
        return $myLocation;
    }

    public function beforeSave($insert)
    {
        $dao=Yii::$app->db;
        $voc=$dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='vocabulary'")->queryOne();
        if(!$voc){
            $dao->createCommand()->insert('depend', [
                'table_name'=>'vocabulary',
                'last_update' =>time(),
            ])->execute();
        }
        else{
            $dao->createCommand()->update('depend', ['last_update' =>time()], 'table_name="vocabulary"')->execute();
        }
        return parent::beforeSave($insert);
    }


    //for api
    public function fields()
    {
        $lang=Yii::$app->language;
        $fields = [
            'id','key',
            'value' => function ($model) use($lang){
                if($lang=='ky'){if($model->value_ky){$model->value=$model->value_ky;}}
                else if($lang=='en'){if($model->value_en){$model->value=$model->value_en;}}
                return $model->value;
            },
            'ordered_id','parent',
        ];
        return $fields;
    }
}
