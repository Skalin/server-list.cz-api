<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\v1\models\Server */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="server-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'port')->textInput() ?>

    <?= $form->field($model, 'query_port')->textInput() ?>

    <?php $services = \app\modules\v1\models\Service::find()->asArray()->all(); ?>
    <?= $form->field($model, 'service_id')->dropDownList(\yii\helpers\ArrayHelper::map($services, 'id', 'name'), ['prompt' => 'Select service']) ?>

    <?= $form->field($model, 'registrator_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'username')) ?>

    <?= $form->field($model, 'user_id')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'id', 'username')) ?>

    <?= $form->field($model, 'image_url')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
