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
use frontend\models\Vocabulary;
/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $text
 */
class News extends \yii\db\ActiveRecord
{

    public $file;
    public $crop_info;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
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


    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    public function getCommentsCount()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id'])->count();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'text'], 'required'],
            [['category_id', 'views', 'img','date','main_news'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],

            ['crop_info', 'safe'],
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
            'description' => Yii::t('app', 'Короткое описание'),
            'text' => Yii::t('app', 'Текст'),
            'img' => Yii::t('app', 'Рисунок'),
            'category_id' => Yii::t('app', 'Категория'),
            'views' => Yii::t('app', 'Просмотры'),
            'date' => Yii::t('app', 'Дата'),
            'main_news' => Yii::t('app', 'Главная новость'),
            'file' => Yii::t('app', 'Фото'),
        ];
    }



    function getThumbs()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/news/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = $image;
                if (basename($image) == $this->img) {
                    $new_value = $image;
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
            return $result;
        }
    }

    function getThumbImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/news/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = Html::img(str_replace([Yii::getAlias('@webroot'), DIRECTORY_SEPARATOR], [Yii::getAlias('@web'), '/'], $image));
                if (basename($image) == $this->img) {
                    $new_value = Html::img(str_replace([Yii::getAlias('@webroot'), DIRECTORY_SEPARATOR], [Yii::getAlias('@web'), '/'], $image));
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
        }
        return $result;
    }

    public function getThumb(){
        if($this->img)
            return Html::img(Url::base()."/images/news/{$this->id}/thumbs/{$this->img}");
        else
            return "";
    }

    public function getMainImg(){
        if($this->img) {
            return Html::img(Url::base() . "/images/news/{$this->id}/{$this->img}");
        }
        else{
            return Html::img(Url::base() . "/images/site/template.png");
        }
    }

    public function beforeSave($insert)
    {
        $this->file = UploadedFile::getInstances($this, 'file');
        return parent::beforeSave($insert);
    }



    public function getCategory(){
        return $this->hasOne(Vocabulary::className(), ['id' => 'category_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_favorited']);
    }

    function getImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/news/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/news/{$this->id}/"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);

            $index = 0;
            foreach ($images as $image) {
                $result[] = Html::img(str_replace([Yii::getAlias('@webroot'), DIRECTORY_SEPARATOR], [Yii::getAlias('@web'), '/'], $image));
                if (basename($image) == $this->img) {
                    $new_value = Html::img(str_replace([Yii::getAlias('@webroot'), DIRECTORY_SEPARATOR], [Yii::getAlias('@web'), '/'], $image));
                    unset($result[$index]);
                    array_unshift($result, $new_value);
                }
                $index++;
            }
        }
        return $result;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (count($this->file)) {
            $dir = Yii::getAlias("@webroot/images/news/{$this->id}");
            $thumbDir = Yii::getAlias("@webroot/images/news/{$this->id}/thumbs");
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);

            $imagine = Image::getImagine();
            foreach ($this->file as $file) {
                $file->saveAs("{$dir}" . DIRECTORY_SEPARATOR . "{$file->baseName}.{$file->extension}");
                $image = $imagine->open(
                    Yii::getAlias('@webroot/images/news')
                    . "/{$this->id}/{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(440, 270))->save(Yii::getAlias('@webroot/images/news/')
                    ."{$this->id}/". $file->baseName.".".$file->extension, ['quality' => 100]);

                Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 440, 270)->save($dir . '/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(135, 100))->save(Yii::getAlias('@webroot/images/news/')
                    .
                    "{$this->id}/thumbs/{$file->baseName}.{$file->extension}", ['quality' => 100]);

                Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 135, 100)->save($dir . '/thumbs/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);
            }
        }
    }
}
