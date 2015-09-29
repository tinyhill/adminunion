<?php

/**
 * @see http://www.brightyoursite.com/blog/2010/06/01/use-php-to-get-google-page-rank/
 */
class Pr extends CModel {

	function attributeNames () {
	}

	// Convert string to number
	function _StrToNum ($Str, $Check, $Magic) {

		$Int32Unit = 4294967296; // 2^32
		$length = strlen($Str);
		for ($i = 0; $i < $length; $i++) {
			$Check *= $Magic;
			/*	If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
				the result of converting to integer is undefined
				refer to http://www.php.net/manual/en/language.types.integer.php	*/
			if ($Check >= $Int32Unit) {
				$Check = ($Check - $Int32Unit * (int)($Check / $Int32Unit));
				//if the check less than -2^31
				$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
			}
			$Check += ord($Str{$i});
		}
		return $Check;
	}

	// Generate a proper hash for an url
	function _HashURL ($String) {

		$Check1 = $this->_StrToNum($String, 0x1505, 0x21);
		$Check2 = $this->_StrToNum($String, 0, 0x1003F);

		$Check1 >>= 2;
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000) | ($Check1 & 0x3FFF);

		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) << 2) | ($Check2 & 0xF0F);
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000);

		return ($T1 | $T2);

	}

	// Generate a checksum for the hash
	function _CheckHash ($Hashnum) {

		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf('%u', $Hashnum);
		$length = strlen($HashStr);
		for ($i = $length - 1; $i >= 0; $i--) {
			$Re = $HashStr{$i};
			if (1 === ($Flag % 2)) {
				$Re += $Re;
				$Re = (int)($Re / 10) + ($Re % 10);
			}
			$CheckByte += $Re;
			$Flag++;
		}
		$CheckByte %= 10;
		if (0 !== $CheckByte) {
			$CheckByte = 10 - $CheckByte;
			if (1 === ($Flag % 2)) {
				if (1 === ($CheckByte % 2)) {
					$CheckByte += 9;
				}
				$CheckByte >>= 1;
			}
		}
		return '7' . $CheckByte . $HashStr;

	}

	// Use curl the get the file contents
	function _curl ($url, $cookie = false) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		$cookie && curl_setopt($ch, CURLOPT_COOKIE, 'PREF=ID=7dcfd7cc17b3a5ee:FF=0:NW=1:TM=1368981580:LM=1368981580:S=V089pDt-LEdqQfeP');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;

	}

	// Get the Google Pagerank
	function query ($q) {

		$tbr = 'http://toolbarqueries.google.com.hk/tbr?client=navclient-auto&features=Rank&ch=' . $this->_CheckHash($this->_HashURL($q)) . '&q=info:' . $q;
		$data = $this->_curl($tbr);
		$pos = strpos($data, 'Rank_');
		if ($pos !== false) {
			$pagerank = substr($data, $pos + 9);
			return trim($pagerank);
		} else {
			exit('0');
		}

	}

	// 真实性校验
	function check ($q) {

		Yii::import('ext.simple_html_dom', true);
		$url = 'http://www.google.com.hk/search?hl=zh-CN&q=info:' . $q;
		if (!$ret = $this->_curl($url, true)) {
			die('无法访问');
		}
		$ret = str_get_html($ret);
		if ($ret && $ret = $ret->find('cite', 0)) {
			if (stripos($ret, $q) !== false) {
				return '真实';
			} else {
				$ret = explode('/', $ret->plaintext);
				return '劫持 ' . $ret[0];
			}
		} else {
			return '未知';
		}

	}

	// 输出图片
	function gif ($q, $size) {

		$pr = $this->query($q);
		$images = Yii::getPathOfAlias('application.components.pr.images');
		$image = imagecreatefromgif($images . '/' . $size . $pr . '.gif');
		header('Content-type:image/gif');
		header('Cache-control:max-age=3600');
		header('Expires:' . gmdate('D, d M Y H:i:s', time() + 3600 * 24) . ' GMT');
		imagegif($image);
		imagedestroy($image);

	}

}