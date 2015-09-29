<?php

class IndexController extends CController {

	function actionBaidu ($q = '', $lm = '', $deep = false) {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->baidu($q, $lm, $deep);
		if (is_array($data)) {
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		} else {
			echo $data;
		}

	}

	function actionGoogle ($q = '', $as_qdr = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->google($q, $as_qdr);
		echo $data;

	}

	function actionSogou ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->sogou($q);
		echo $data;

	}

	function actionSoso ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->soso($q);
		echo $data;

	}

	function actionYoudao ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->youdao($q);
		echo $data;

	}

	function actionBing ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->bing($q);
		echo $data;

	}

	function actionYahoo ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->yahoo($q);
		echo $data;

	}

	function actionSo ($q = '') {

		Yii::import('application.models.Index');
		$model = new Index();
		$data = $model->so($q);
		echo $data;

	}

}