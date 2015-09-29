<?php

class AlexaController extends CController {

	function actionIndex ($q = '') {

		Yii::import('application.models.Alexa');
		$model = new Alexa();
		$data = $model->query($q);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);

	}

}