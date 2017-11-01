
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
?>
<div class="news-view">

    <div class="minor_heading"><?= Html::encode($this->title) ?></div>
	<div class="news_text"><?=$model->text;?></div>
  
  
</div>



