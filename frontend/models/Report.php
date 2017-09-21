<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use frontend\models\Vocabulary;
use frontend\models\Comments;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "report".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property integer $views
 * @property string $author
 * @property integer $user_id
 * @property integer $authority_id
 * @property integer $category_id
 * @property double $lon
 * @property double $lat
 * @property integer $city_id
 * @property string $text
 * @property integer $anonymous
 * @property string $email
 * @property string $contact
 * @property array $images
 */
class Report extends \yii\db\ActiveRecord
{
    public $data = [];
    public $images = []; //android, ios uploaded images

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'authority_id', 'category_id', 'text', 'city_id'], 'required'],
            [['category_id', 'lon', 'author', 'lat', 'city_id', 'text', 'anonymous', 'email', 'contact', 'type_id','images'], 'safe'],
            //[['email'], 'email'],
            [['date'], 'safe'],
            [['views', 'authority_id', 'category_id', 'city_id', 'anonymous', 'type_id'], 'integer'],
            [['lon', 'lat'], 'number'],
            [['text'], 'string'],
            [['title', 'author', 'email', 'contact'], 'string', 'max' => 255],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Заголовок'),
            'date' => Yii::t('app', 'Дата и время'),
            'views' => Yii::t('app', 'Просмотры'),
            'author' => Yii::t('app', 'Автор'),
            'authority_id' => Yii::t('app', 'гос.структура'),
            'category_id' => Yii::t('app', 'Сектор коррупции'),
            'lon' => Yii::t('app', 'Lon'),
            'lat' => Yii::t('app', 'Lat'),
            'city_id' => Yii::t('app', 'Город'),
            'text' => Yii::t('app', 'Текст'),
            'anonymous' => Yii::t('app', 'Анонимное сообщение'),
            'email' => Yii::t('app', 'Электронная почта'),
            'contact' => Yii::t('app', 'Контакты'),
        ];
    }

    public function getAuthority()
    {
        return $this->hasOne(Authority::className(), ['id' => 'authority_id']);
    }


    public function getDropdownItems($data_key = "")
    {
        $items = ArrayHelper::map(Vocabulary::find()->where(['parent' => 0, 'key' => $data_key])->all(), 'id', 'value');
        $new_arr = [];
        foreach ($items as $key => $val) {
            $new_arr[$val] = ArrayHelper::map(Vocabulary::find()->where(['parent' => $key])->all(), 'id', 'value');
        }
        return $new_arr;
    }

    public function getAuthorities()
    {
        $items = ArrayHelper::map(Authority::find()->where(['category_id' => 0])->all(), 'id', 'title');
        $new_arr = [];
        foreach ($items as $key => $val) {
            $new_arr[$val] = ArrayHelper::map(Authority::find()->where(['category_id' => $key])->all(), 'id', 'title');
        }
        return $new_arr;
    }

    public function getSingleDrop($key){
        return ArrayHelper::map(Vocabulary::find()->where(['key' => $key])->all(),'id', 'value');
    }


    public function getDepartment()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    public function getType()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'type_id']);
    }

    public function getCity()
    {
        return $this->hasOne(Vocabulary::className(), ['id' => 'city_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->count();
    }


    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->date = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if($this->images){
            $dir=Yii::getAlias('@frontend').'/web/images/report/';
            $tosave=$dir.$this->id;
            if (!file_exists($tosave)) {
                mkdir($tosave);
            }
            foreach($this->images as $k=>$image){
                $ts=time();
                $rand=$this->id."_".$k."_".$ts;
                $imgname = $rand.".jpg";
                $decoded=base64_decode($image);
                imagejpeg(imagecreatefromstring($decoded),$tosave .'/'. $imgname,80);
                $this->resizeImage($tosave,$rand);
            }
        }
    }

    protected function resizeImage($dir,$imageName){

        if (Yii::$app->request->serverName=='anticor.loc') {
            Image::$driver = [Image::DRIVER_GD2];
        }
        $imagine=Image::getImagine()->open($dir.'/'.$imageName.'.jpg');
        $imagine->thumbnail(new Box(400, 400))->save($dir.'/'.$imageName.'_m.jpg');
        $imagine->thumbnail(new Box(80, 80))->save($dir.'/'.$imageName.'_t.jpg');
    }

}
