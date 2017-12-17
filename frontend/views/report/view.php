<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

echo newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=fancybox]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);

/* @var $this yii\web\View */
/* @var $model app\models\Report */

$this->title = $model->title;
/*$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="report-view mobile_padder">
    <div class="italic_header" style="color: #3b3b3b"><?= Html::encode($this->title) ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', date("H:i",strtotime($model->date)), ['class' => 'news_date']);
    echo Html::beginTag('span', ['class' => 'news_view_count', 'style' => 'margin-left:10px;float:none']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    ?>
    <div class="clear" style="margin-top: 10px"></div>
    <div class="quotes"></div>
    <div class="report-text" style="margin-bottom: 25px;">
        <div class="report-padder">
            <?= $model->text; ?>
            <div class="clear"></div>
            <div class="comment-author" style="font-style: normal;font-family: 'PT Sans',sans-serif;margin-top:10px">
                <?= $model->author; ?>
            </div>
        </div>
    </div>
    <?php $images = $model->getImages();
    foreach ($images as $key => $val) {
        echo Html::beginTag('span', ['data-thumb' => $key]);
        echo Html::a(Html::img($key), $val, ['class' => 'gallery-view-img', 'rel' => 'fancybox']);
        echo Html::endTag('span');
    }
    ?>
    <div class="report-cats">
        <?php if($model->authority):?>
        <div class="cat-row">

            <span><?= Yii::t('app', 'Гос.орган: ') ?></span>
            <?= $model->authority->title; ?>
        </div>
        <?php endif;?>

        <?php if($model->department):?>
        <div class="cat-row">
            <span><?= Yii::t('app', 'Сектор коррупции: ') ?></span>
            <?= $model->department->value; ?>
        </div>
        <?php endif;?>

        <?php if($model->city):?>
        <div class="cat-row">
            <span><?= Yii::t('app', 'Местоположение: ') ?></span>
            <?= $model->city->value; ?>
        </div>
        <?php endif;?>

        <?php if($model->type):?>
        <div class="cat-row">
            <span><?= Yii::t('app', 'Тип обращения: ') ?></span>
            <?= $model->type->value; ?>
        </div>
        <?php endif;?>
    </div>


    <div class="clear" style="margin-top: 20px;"></div>
<?php if($model->lat){
    echo Html::tag('div','',['class'=>'map','id'=>'map_view']);
}
?>

    <div class="clear" style="margin-top: 20px;"></div>

    <?php
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
        if ($model->status == 0) {
            echo Html::tag('span', 'Одобрить', ['class' => 'btn-moderate btn btn-success', 'data-status' => 1]);
        } else {
            echo Html::tag('span', 'Блокировать', ['class' => 'btn-moderate btn btn-danger', 'data-status' => 0]);
        }
    }
    ?>

    <div class="share_buttons" style="height: 35px;">
        <span class="share_label">Поделиться в соц.сетях: </span>
        <script type="text/javascript">(function () {
                if (window.pluso) if (typeof window.pluso.start == "function") return;
                if (window.ifpluso == undefined) {
                    window.ifpluso = 1;
                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })();</script>
        <div class="pluso" data-background="none;"
             data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14"
             data-services="facebook,vkontakte,odnoklassniki,twitter"
             data-url="http://anticorruption.kg/report/<?= $model->id ?>"
             data-title="Антикоррупционный портал Кыргызской Республики">
        </div>
    </div>
    <div class="clear"></div>
    <?php
    echo Html::hiddenInput('lat', $model->lat, ['class' => 'report_lat']);
    echo Html::hiddenInput('lon', $model->lon, ['class' => 'report_lon']);
    ?>



    <?php Pjax::begin(['id' => 'pjax-comment']); ?>
    <div class="comment-box">
        <div class="top_marginer"></div>
        <div class="comments">Комментарии(<?= $model->commentsCount; ?>)</div>
        <div class="clear"></div>
        <?php $comments = $model->comments;
        foreach ($comments as $item) {
            echo Html::beginTag('div', ['class' => 'comment-block']);
            echo Html::tag('div', '', ['class' => 'comment-avatar']);
            echo Html::beginTag('div',['style'=>'height:22px;']);
            echo Html::tag('div', $item->name, ['class' => 'comment-author','style'=>'float:left;margin-right:10px;']);
            echo Html::tag('span', date("H:i",strtotime($model->date)), ['class' => 'comment-date']);
            echo Html::tag('span', Yii::$app->formatter->asDate($item->date), ['class' => 'comment-date']);
            echo Html::endTag('div');
            echo Html::tag('div', $item->text, ['class' => 'comment-text']);

            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
                echo Html::tag('div', '', ['class' => 'clear']);
                echo Html::tag('button', 'Редактировать', ['class' => 'btn btn-primary comment-update', 'style' => 'margin:10px 10px 0 0;', 'data-id' => $item->id]);
                echo Html::tag('button', 'Удалить', ['class' => 'btn btn-danger comment-delete', 'style' => 'margin:10px 0 0 0;', 'data-id' => $item->id]);
            }
            echo Html::endTag('div');
        }
        ?>
        <div class="top_marginer_30"></div>
        <div class="comments">Оставить комментарии</div>
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['comments/create']),
            'id' => 'comment-add',
            'options' => [
                'class' => 'comments-gq',
            ]]); ?>

        <?php
        if (Yii::$app->user->isGuest) {
            echo $form->field($comment, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Введите имя', 'class' => 'form-control sharper'])->label(false);
            echo $form->field($comment, 'email')->textInput(['maxlength' => true, 'placeholder' => '@электронная почта', 'class' => 'form-control sharper'])->label(false);
        } else {
            echo $form->field($comment, 'name')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false);
            echo $form->field($comment, 'email')->hiddenInput(['value' => Yii::$app->user->identity->email])->label(false);
        }
        echo $form->field($comment, 'report_id')->hiddenInput(['value' => $model->id])->label(false); ?>

        <?= $form->field($comment, 'text')->textarea(['maxlength' => true, 'rows' => 9, 'placeholder' => 'Введите текст комментария', 'class' => 'form-control comment-input-text'])->label(false); ?>

        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LegmzEUAAAAAGucd6quo8hn50mfC6xt_WF9u43P"></div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Отправить комментарий'), ['class' => 'send-comment btn btn-danger']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php Pjax::end(); ?>
</div>

<script type="text/javascript">
    $('body').on('submit', '#comment-add', function (e) {
        e.preventDefault();
        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function (response) {
                if (response == "no") {
                    alert("Пожалуйста, потвердите что вы не робот!");
                }
                else {
                    $.pjax.reload({container: "#pjax-comment"});
                    alert("Спасибо, Ваш комментарий отправлен и будет добавлен после модерации");
                }
            }
        });
        return false;
    });

    $('body').on('click', '.btn-moderate', function () {
        var status = $(this).attr('data-status');
        var thisOne = $(this);
        $.ajax({
            url: '/report/status',
            type: 'post',
            data: {id:<?=$model->id?>, status: status},
            success: function (response) {
                if (response == 1) {
                    alert("Обращение одобрено и опубликовано  на сайте");
                    thisOne.removeClass('btn-success').addClass('btn-danger');
                    thisOne.text("Блокировать");
                    thisOne.attr('data-status', 0);
                }
                else {
                    alert("Обращение заблокировано");
                    thisOne.removeClass('btn-danger').addClass('btn-success');
                    thisOne.text("Одобрить");
                    thisOne.attr('data-status', 1);
                }
            }
        });

        return false;
    });

    $('body').on('click', '.comment-update', function (e) {
        e.preventDefault();

        var thisOne = $(this);
        if (!thisOne.hasClass('sender')) {
            thisOne.addClass('sender');
            thisOne.before("<textarea class='form-control editable-comment comment-input-text'>" + thisOne.siblings('.comment-text').text() + "</textarea>");
        }
    });
    $('body').on('click', '.sender', function (e) {
        e.preventDefault();
        var commentId = $(this).attr('data-id');
        var thisOne = $(this);
        var newText = thisOne.siblings('.editable-comment').val();

        $.ajax({
            url: '/comments/edit',
            type: 'post',
            data: {id: commentId, text: newText},
            success: function () {
                $.pjax.reload({container: "#pjax-comment"});
            }
        });
        return false;
    });

    $('body').on('click', '.comment-delete', function (e) {
        e.preventDefault();
        var commentId = $(this).attr('data-id');
        var thisOne = $(this);
        $.ajax({
            url: '/comments/remove',
            type: 'post',
            data: {id: commentId},
            success: function () {
                $.pjax.reload({container: "#pjax-comment"});
            }
        });
        return false;
    });

</script>
