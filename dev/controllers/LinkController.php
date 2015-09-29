<?php

class LinkController extends CController {

	function actionIndex ($q = '', $ua = '', $links = '') {

		Yii::import('application.models.Link');
		$model = new Link();
		$data = $model->query($q, $ua, $links);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);

	}

	function actionBacklink ($q = '', $url = '', $ua = '') {

		Yii::import('application.models.Link');
		$model = new Link();
		echo json_encode($model->backlink($q, $url, $ua), JSON_UNESCAPED_UNICODE);

	}

}