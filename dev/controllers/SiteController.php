<?php

class SiteController extends CController {

	function actionIndex ($q) {

		Yii::import('application.models.Site');
		$model = new Site();
		$data = $model->query($q);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);

	}

}