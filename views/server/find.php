<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="server-form">

	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'ip')->textInput() ?>

	<?= $form->field($model, 'port')->textInput() ?>
	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

    <?php if ($server): ?>

        <div class="server-view">

            <h1><?= Html::encode($this->title) ?></h1>

            <p>
				<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $server->id], ['class' => 'btn btn-primary']) ?>
				<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $server->id], [
					'class' => 'btn btn-danger',
					'data' => [
						'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					],
				]) ?>
            </p>

			<?= \yii\widgets\DetailView::widget([
				'model' => $server,
				'attributes' => [
					'id',
					'name',
					'domain',
					'ip',
					'password',
					'port',
					'query_port',
					'service_id',
					'registrator_id',
					'user_id',
					'image_url:url',
					'description:ntext',
					'created_at',
					'updated_at',
				],
			]) ?>

        </div>


    <?php endif; ?>

</div>
