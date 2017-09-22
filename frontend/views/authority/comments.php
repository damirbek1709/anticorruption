<?

use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>



<?php Pjax::begin(['id' => 'pjax-comment']);?>
    <div class="comment-box">
        <div class="top_marginer"></div>
        <?php $comments = $model->comments;
        foreach ($comments as $item) {
            echo Html::beginTag('div', ['class' => 'comment-block']);
            echo Html::tag('div', '', ['class' => 'comment-avatar']);
            echo Html::tag('div', $item->name, ['class' => 'comment-author']);
            echo Html::tag('div', $item->text, ['class' => 'comment-text']);
            echo Html::tag('span', Yii::$app->formatter->asTime($comment->date), ['class' => 'comment-date']);
            echo Html::tag('span', Yii::$app->formatter->asDate($comment->date), ['class' => 'comment-date']);
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
        echo $form->field($comment, 'category_id')->hiddenInput(['value' => 2])->label(false);
        ?>
        <?= $form->field($comment, 'category_id')->hiddenInput(['value' => $model->id])->label(false); ?>


        <?= $form->field($comment, 'text')->textarea(['maxlength' => true, 'rows' => 9, 'placeholder' => 'Введите текст комментария', 'class' => 'form-control comment-input-text'])->label(false); ?>

        <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LegmzEUAAAAAGucd6quo8hn50mfC6xt_WF9u43P"></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Отправить комментарий'), ['class' => 'send-comment btn btn-danger']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php Pjax::end();?>

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
                if(response == "No") {
                    alert(response);
                }
                else{
                    $.pjax.reload({container: "#pjax-comment"});
                }
            }
        });
        return false;
    });
</script>
