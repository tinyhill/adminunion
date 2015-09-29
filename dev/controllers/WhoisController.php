<?php

class WhoisController extends CController {

	function actionIndex ($q, $deep = false) {

		Yii::import('application.models.Whois');
		$model = new Whois();
		echo $model->query($q, $deep);

	}

	function actionReginfo ($q) {

		Yii::import('application.models.Whois');
		$model = new Whois();
		echo json_encode($model->reginfo($q), JSON_UNESCAPED_UNICODE);

	}

}