<?php

class PrController extends CController {

	function filters () {

		return array(
//			'AjaxOnly',
//			array('application.filters.AjaxFilter')
			array(
				'COutputCache + index, check',
				'duration' => 10,
				'varyByParam' => array('q'),
			)
		);

	}

	function actionIndex ($q = '') {

		Yii::import('application.models.Pr');
		$model = new Pr();
		echo $model->query($q);
	}

	function actionCheck ($q = '') {

		Yii::import('application.models.Pr');
		$model = new Pr();
		echo $model->check($q);

	}

}