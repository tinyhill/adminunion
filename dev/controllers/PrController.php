<?php

class PrController extends CController {

	function actionIndex ($q = '') {

		Yii::import('application.models.Pr');
		$model = new Pr();
		echo $model->query($q);

	}

	function actionCheck ($q = '') {

		Yii::import('application.models.Pr');
		$model = new Pr();
		var_dump($model->check($q));

	}

	function actionGif ($q = '', $size = 'xl') {

		Yii::import('application.models.Pr');
		$model = new Pr();
		$model->gif($q, $size);

	}

}