<?php

/**
 * 计算图形验证码值
 * @class Validate
 */
define('WORD_WIDTH', 9);
define('WORD_HIGHT', 13);
define('OFFSET_X', 7);
define('OFFSET_Y', 3);
define('WORD_SPACING', 4);

class Validate {

	public function setImage($Image) {
		$this -> ImagePath = $Image;
	}

	public function getData() {
		return $data;
	}

	public function getResult() {
		return $DataArray;
	}

	public function getHec() {
		$res = imagecreatefromjpeg($this -> ImagePath);
		$size = getimagesize($this -> ImagePath);
		$data = array();
		for ($i = 0; $i < $size[1]; ++$i) {
			for ($j = 0; $j < $size[0]; ++$j) {
				$rgb = imagecolorat($res, $j, $i);
				$rgbarray = imagecolorsforindex($res, $rgb);

				if ($i == 0 || $i == $size[1] - 1 || $j == 0 || $j == $size[0] - 1) {

					$data[$i][$j] = 0;
					continue;

				}

				if ($rgbarray['red'] < 125 || $rgbarray['green'] < 125 || $rgbarray['blue'] < 125) {
					$data[$i][$j] = 1;
				} else {
					$data[$i][$j] = 0;
				}
			}
		}
		$this -> DataArray = $data;
		$this -> ImageSize = $size;
	}

	public function run() {
		$result = '';
		// 查找4个数字
		$data = array('', '', '', '');

		$tempData = array();

		$size = getimagesize($this -> ImagePath);
		$flag = 0;
		for ($j = 0; $j < $size[0]; ++$j) {

			$temp = array();

			//echo $j.'<br/>';
			for ($i = 5; $i < 16; ++$i) {
				if ($this -> DataArray[$i][$j] == 1) {
					$flag = 1;

				}
				//echo $this->DataArray[$i][$j];

				array_push($temp, $this -> DataArray[$i][$j]);
			}

			if (0 == array_sum($temp) && 1 == $flag) {

				$flag = 2;
				//echo 'tests';

			}

			if (1 == $flag) {

				$tempData[sizeof($tempData)] = $temp;
				//print_r($temp);
			}

			if (2 == $flag) {

				$flag = 0;

				$len = sizeof($tempData);

				$b = array();

				foreach ($tempData as $arrayKey) {
					foreach ($arrayKey as $k => $v) {
						$b[$k][] = $v;
					}

				}

				$tempStr = '';

				foreach ($b as $arrayKey) {
					$tempStr .= join('', $arrayKey);
				}

				$max = 0.0;
				$num = 0;

				foreach ($this->Keys as $key => $value) {
					$percent = 0.0;

					similar_text($value, $tempStr, $percent);

					if (intval($percent) > $max) {
						$max = $percent;
						$num = $key;

						if (intval($percent) > 95) {
							$result .= $key;
							break;
						}
					}
				}

				$tempData = array();

			}

		}

		$this -> data = $result;
		// 查找最佳匹配数字
		$temp = str_replace('=', '', $result);

		if (stripos($temp, '-')) {

			$temp = explode('-', $temp);
			$finalNum = $temp[0] - $temp[1];

		} else {

			$temp = explode('+', $temp);
			$finalNum = $temp[0] + $temp[1];

		}

		//return $result.$finalNum;
		return $finalNum;
	}

	public function Draw() {
		for ($i = 0; $i < $this -> ImageSize[1]; ++$i) {
			for ($j = 0; $j < $this -> ImageSize[0]; ++$j) {
				echo $this -> DataArray[$i][$j];
			}
			echo '\n';
		}
	}

	public function __construct() {
		$this -> Keys = array(
			'0' => '0011110001111110011001001100001111000011110000101100001111000011011001100111111000111100',
			'1' => '001100111100111100001100001100001100001100001100001100111111111111',
			'2' => '01111101111111100001100000110000110000110000110000110000110000011111111111111',
			'3' => '0111110011111111100000110000001100111110001111100000011100000011100001111111111001111100',
			'4' => '0001111000011110001101100011011001100110110001101111111111111111000001100000011000000110',
			'5' => '1111111111111111110000001100000011111000111111100000011100000011100001111111111001111100',
			'6' => '0001111001111111011000011110000011011100111111111100001111000011111000110111111000111100',
			'7' => '11111111111111000001100001100001100000110000110000110000011000011000001100000',
			'8' => '0011111001111111110000111110001001111100011111101100011111000011111000110111111000111100',
			'9' => '0011110001111110110001111100001111000011011111110011101100000011100001101111111001111000',
			'+' => '000000000000000000000010000000010000000010000000010000111111111000010000000010000000010000000010000',
			'-' => '00000000000000000000000000000000000111111111111110000000000000000000000000000',
			'=' => '000000000000000000000000000000000000000000000111111111000000000000000000111111111000000000000000000'
		);
	}

	protected $ImagePath;
	protected $DataArray;
	protected $ImageSize;
	protected $data;
	protected $Keys;
	protected $NumStringArray;

}
?>