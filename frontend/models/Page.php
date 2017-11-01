<?php
namespace frontend\models;
use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $title_ky
 * @property string $text_ky
 * @property string $title_en
 * @property string $text_en
 * @property string $description
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['title_ky', 'text_ky','title_en', 'text_en','description'], 'safe'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'text' => Yii::t('app', 'Текст'),
            'title_en' => Yii::t('app', 'Заголовок на английском'),
            'text_en' => Yii::t('app', 'Текст на английском'),
            'title_ky'=>Yii::t('app', 'Заголовок на кыргызском'),
            'text_ky'=>Yii::t('app', 'Текст на кыргызском'),
        ];
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
                    $this->title = $this->{"title"} . "<span style=color:black> (This material is not availabe in English yet)</span>";
                    $this->text = $this->{"text"};
                }
                break;
            case "ky":
                if ($this->title_ky != null) {
                    $this->text = $this->{"text_ky"};
                    $this->title = $this->{"title_ky"};
                } else {
                    $this->title = $this->{"title"} . "<span style=color:black> (Кыргыз тилинде бул материал азырынча жеткиликтүү эмес)</span>";
                    $this->text = "";
                }
                break;
            default:
                $this->text = $this->{"text"};
                $this->title = $this->{"title"};
        }
    }

    public function fields()
    {
        $lang=Yii::$app->language;
        return [
            'id',
            'title' => function ($model) use($lang){
                if($lang=='ky'){if($model->title_ky){$model->title=$model->title_ky;}}
                else if($lang=='en'){if($model->title_en){$model->title=$model->title_en;}}
                return $model->title;
            },
            'text' => function ($model) use($lang){
                if($lang=='ky'){if($model->text_ky){$model->text=$model->text_ky;}}
                else if($lang=='en'){if($model->text_en){$model->title=$model->text_en;}}
                return $model->text;
            },
            'description'
        ];
    }

    public function beforeSave($insert)
    {
        //depend table holds timestamp of last table modification. it's for api
        $dao = Yii::$app->db;
        $voc = $dao->createCommand("SELECT * FROM `depend` WHERE `table_name`='page'")->queryOne();
        if (!$voc) {
            $dao->createCommand()->insert('depend', [
                'table_name' => 'page',
                'last_update' => time(),
            ])->execute();
        } else {
            $dao->createCommand()->update('depend', ['last_update' => time()], 'table_name="page"')->execute();
        }
        return parent::beforeSave($insert);
    }
}
