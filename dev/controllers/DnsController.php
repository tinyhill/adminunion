<?php

class DnsController extends CController {

	function actionIndex ($q = '', $type = 'ANY') {

		Yii::import('application.models.Dns');
		$model = new Dns();
		$data = $model->query($q, $type);
		echo json_encode($data);

	}

}