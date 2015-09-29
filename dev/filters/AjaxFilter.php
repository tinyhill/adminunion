<?php

class AjaxFilter extends CFilter {

	function preFilter ($filterChain) {

		if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
			ob_start('ob_gzhandler');
			$filterChain->run();
			ob_end_flush();
		} else {
			throw new CHttpException(403);
		}

	}

}