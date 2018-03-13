<?php

namespace frontend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\imagine\Image;
use yii\helpers\Json;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\db\Query;
use frontend\models\Comments;


/**
 * This is the model class for table "authority".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $img
 */
class Authority extends \yii\db\ActiveRecord
{

    public $image;
    public $crop_info;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['text'], 'safe'],
            [['text'], 'string'],
            [['category_id'], 'integer'],
            [['title', 'img'], 'string', 'max' => 255],
            [
                'image',
                'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],
            [['crop_info','title_ky', 'text_ky','title_en', 'text_en'], 'safe'],
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['category_id' => 'id'])->where(['status'=>1])->orderBy(['date' => SORT_ASC]);
    }

    public function getReports()
    {
        return $this->hasMany(Report::className(), ['authority_id' => 'id'])->andWhere(['type_id' => 134])->orderBy(['date' => SORT_ASC]);
    }

    public function getBrief()
    {
        return $this->hasMany(Report::className(), ['authority_id' => 'id'])->andWhere(['type_id' => 135])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['category_id' => 'id'])->where(['status'=>1])->count();
    }

    function afterFind()
    {
        if ($this->scenario == 'search') {
            $this->translate(Yii::$app->language);
        }
    }

    function translate($language)
    {
        switch ($language) {
            case "en":
                if ($this->title_en) {
                    $this->text = $this->{"text_en"};
                    $this->title = $this->{"title_en"};
                } else {
                    $this->title = $this->{"title"};
                    $this->text = $this->{"text"};
                }
                break;
            case "ky":
                if ($this->title_ky != null) {
                    $this->text = $this->{"text_ky"};
                    $this->title = $this->{"title_ky"};
                } else {
                    $this->title = $this->{"title"};
                    $this->text = $this->{"text"};
                }
                break;
            default:
                $this->text = $this->{"text"};
                $this->title = $this->{"title"};
        }
    }


    /*public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['rating','votes'], // update 1 attribute 'created' OR multiple attribute ['created','updated']
                    //ActiveRecord::EVENT_BEFORE_UPDATE => '', // update 1 attribute 'created' OR multiple attribute ['created','updated']
                ],
                'value' => function ($event) {
                    return 0;
                },
            ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Госорган'),
            'text' => Yii::t('app', 'Текст о госоргане'),
            'rating' => Yii::t('app', 'Рейтинг'),
            'votes' => Yii::t('app', 'Кол-во голосов'),
            'img' => Yii::t('app', 'Рисунок'),
            'title_ky'=>Yii::t('app', 'Госорган на кыргызском'),
            'text_ky'=>Yii::t('app', 'Текст о госоргане на кыргызском'),
            'title_en' => Yii::t('app', 'Заголовок на английском'),
            'text_en' => Yii::t('app', 'Текст на английском'),
        ];
    }

    public function beforeValidate()
    {
        $this->image = UploadedFile::getInstance($this, 'image');
        return parent::beforeValidate();
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

        if ($this->image)
            $this->img = $this->image->name;
        return parent::beforeSave($insert);
    }

    public static function getRating($id){
        $query = (new Query())->from('rating')->where(['authority_id'=>$id]);
        if($query->count()==0)
            $rating = 0;
        else
            $rating = round($query->sum('rating') / $query->count());
        return $rating;
    }

    public static function getRateCount($id){
        $query = (new Query())->from('rating')->where(['authority_id'=>$id]);
        return $query->count();
    }

    public function getRatingCount()
    {
        return $this->hasMany(Rating::className(), ['authority_id' => 'id'])->count();
    }

    public function getReportCount()
    {
        return $this->hasMany(Report::className(), ['authority_id' => 'id'])->where(['status'=>1])->count();
    }

    function getMainImg()
    {
        if ($this->img) {
            $image = Yii::getAlias("@frontend/web/images/authority/{$this->id}_{$this->img}");
            return Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));

        }
        /*else if (is_dir(Yii::getAlias("@frontend/web/images/authority"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/authority/"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            return Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $images[0]));
        }*/ else {
            $image =  Yii::getAlias("@frontend/web/images/site/herb.png");
            return Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));
        }
    }


    function getMainThumb()
    {
        if ($this->img) {
            $image = Yii::getAlias("@frontend/web/images/authority/s_{$this->id}_{$this->img}");
            return Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $image));

        }
       /* else if (is_dir(Yii::getAlias("@frontend/web/images/authority"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@frontend/web/images/authority/"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            return Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $images[0]));
        }*/ else {
            return Html::img(Url::base() . "/images/site/herb.png",['style'=>'width:70%']);
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        // open image
        if ($this->image) {
            $cropInfo = Json::decode(Yii::$app->request->post()['image_data']);

            $width = (int)$cropInfo['width'];
            $height = (int)$cropInfo['height'];

            $x = $cropInfo["x"];
            $y = $cropInfo["y"];

            $imagine = Image::getImagine();
            $this->image->saveAs(
                Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}_"
                . $this->image->name, ['quality' => 100]);

            $image = $imagine->open(
                Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}_"
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box($image->getSize()->getWidth(), $image->getSize()->getHeight()))
                ->crop(new Point($x, $y), new Box($width, $height))
                ->save(Yii::getAlias('@frontend/web/images/authority')
                    . "/{$this->id}_"
                    . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(400, 400))->save(Yii::getAlias('@frontend/web/images/authority')
                . "/{$this->id}_"
                . $this->image->name, ['quality' => 100]);

            $image->resize(new Box(150, 150))->save(Yii::getAlias('@frontend/web/images/authority')
                . "/s_{$this->id}_".
                $this->image->name, ['quality' => 100]);
        }
    }

    //for api
    public function fields()
    {
        $lang=Yii::$app->language;
        $fields = [
            'id',
            'title' => function ($model) use($lang){
                if($lang=='ky'){if($model->title_ky){$model->title=$model->title_ky;}}
                else if($lang=='en'){if($model->title_en){$model->title=$model->title_en;}}
                return $model->title;
            },
            'text' => function ($model) use($lang){
                if($lang=='ky'){if($model->text_ky){$model->text=$model->text_ky;}}
                else if($lang=='en'){if($model->text_en){$model->text=$model->text_en;}}
                return $model->text;
            },
            'img',
            'parent_id' => function ($model){
                return $model->category_id;
            },
            'rating' => function ($model){
                return Authority::getRating($model->id);
            },
            'reports'=> function ($model) {
                return $model->reportCount;
            }
        ];
        if(Yii::$app->controller->action->id=="index"){
            $fields['comments']= function ($model) {
                return $model->commentsCount;
            };
        }
        else if(Yii::$app->controller->action->id=="view"){
            $fields['votes']= function ($model) {
                return Authority::getRateCount($model->id);
            };
            $fields[]='comments';
        }
        return $fields;
    }
}
