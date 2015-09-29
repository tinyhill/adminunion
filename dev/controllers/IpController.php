<?php

class IpController extends CController {

	function actionIndex ($q) {

		Yii::import('application.models.Ip');
		$model = new Ip();
		var_dump($model->query($q));

	}

	function actionClient () {

		Yii::import('application.models.Ip');
		$model = new Ip();
		var_dump($model->client());

	}

	function actionLocate ($q) {

		Yii::import('application.models.Ip');
		$model = new Ip();
		var_dump($model->locate($q));

	}

}