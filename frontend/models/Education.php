<?php
namespace frontend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "education".
 *
 * @property integer $id
 * @property string $title
 * @property string $date
 * @property string $text
 * @property string $img
 */
class Education extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'date', 'text', 'img'], 'safe'],
            [['date'], 'safe'],
            [['text'], 'string'],
            [['title', 'img'], 'string', 'max' => 255],
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
            'date' => Yii::t('app', 'Дата'),
            'text' => Yii::t('app', 'Текст'),
            'file'=> Yii::t('app', 'Изображения'),
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord && !$this->date){
            $this->date = date("Y-m-d H:i:s");
        }
        $this->file = UploadedFile::getInstances($this, 'file');
        return parent::beforeSave($insert);
    }

    function getMainThumb()
    {
        if ($this->img) {
            return Html::img(Url::base() . "/images/education/{$this->id}/thumbs/{$this->img}");
        } else if (is_dir(Yii::getAlias("@webroot/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/education/{$this->id}/thumbs"), [
                'recursive' => false,
                'except' => ['.gitignore']
            ]);
            return Html::img(str_replace(Yii::getAlias('@webroot'), Yii::getAlias('@web'), $images[0]));
        } else {
            return Html::img(Url::base() . "/images/site/blank.png");
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (count($this->file)) {
            $dir = Yii::getAlias("@webroot/images/education/{$this->id}");
            $thumbDir = Yii::getAlias("@webroot/images/education/{$this->id}/thumbs");
            FileHelper::createDirectory($dir);
            FileHelper::createDirectory($thumbDir);

            $imagine = Image::getImagine();
            foreach ($this->file as $file) {
                $file->saveAs("{$dir}" . DIRECTORY_SEPARATOR . "{$file->baseName}.{$file->extension}");
                $image = $imagine->open(
                    Yii::getAlias('@webroot/images/education')
                    . "/{$this->id}/{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(440, 270))->save(Yii::getAlias('@webroot/images/education/')
                    ."{$this->id}/". $file->baseName.".".$file->extension, ['quality' => 100]);

                Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 440, 270)->save($dir . '/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);

                $image->resize(new Box(135, 100))->save(Yii::getAlias('@webroot/images/education/')
                    .
                    "{$this->id}/thumbs/{$file->baseName}.{$file->extension}", ['quality' => 100]);

               Image::thumbnail($dir . '/' . "{$file->baseName}.{$file->extension}", 135, 100)->save($dir . '/thumbs/' . "{$file->baseName}.{$file->extension}", ['quality' => 100]);
            }
        }
    }

    function getThumbs()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/education/{$this->id}/thumbs"), [
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
        if (is_dir(Yii::getAlias("@webroot/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/education/{$this->id}/thumbs"), [
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

    function getImages()
    {
        $result = [];
        if (is_dir(Yii::getAlias("@webroot/images/education/{$this->id}"))) {
            $images = FileHelper::findFiles(Yii::getAlias("@webroot/images/education/{$this->id}/"), [
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
}
