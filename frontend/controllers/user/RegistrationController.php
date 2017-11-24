<?php
namespace frontend\controllers\user;

use Yii;
use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use dektrium\user\models\RegistrationForm;
use yii\web\NotFoundHttpException;

class RegistrationController extends BaseRegistrationController
{
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var RegistrationForm $model */
        $model = \Yii::createObject(RegistrationForm::className());
        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post())) {
            $this->trigger(self::EVENT_AFTER_REGISTER, $event);
            if (empty($_POST['g-recaptcha-response'])) {
                Yii::$app->session->setFlash('captcha_not_clicked', Yii::t('app', 'Пожалуйста, подтвердите, что вы человек, а не робот'));
            } else {
                $model->register();

                return $this->render('/message', [
                    'title' => \Yii::t('user', 'Your account has been created'),
                    'module' => $this->module,
                ]);
            }
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }
}


?>