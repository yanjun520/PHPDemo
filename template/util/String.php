<?php
class Lib_Util_String {
	
	/**
	 * 拆分字符串
	 */
	public static function mbStringToArray($string, $encoding = 'UTF-8') {
		$arrayResult = array ();
		while ( $iLen = mb_strlen ( $string, $encoding ) ) {
			array_push ( $arrayResult, mb_substr ( $string, 0, 1, $encoding ) );
			$string = mb_substr ( $string, 1, $iLen, $encoding );
		}
		return $arrayResult;
	}
	
	/**
	 * 编辑距离
	 */
	public static function levenshteinDistance($str1, $str2, $costReplace = 1, $encoding = 'UTF-8') {
		$count_same_letter = 0;
		$d = array ();
		$mb_len1 = mb_strlen ( $str1, $encoding );
		$mb_len2 = mb_strlen ( $str2, $encoding );
		
		$mb_str1 = Lib_Util_String::mbStringToArray ( $str1, $encoding );
		$mb_str2 = Lib_Util_String::mbStringToArray ( $str2, $encoding );
		
		for($i1 = 0; $i1 <= $mb_len1; $i1 ++) {
			$d [$i1] = array ();
			$d [$i1] [0] = $i1;
		}
		
		for($i2 = 0; $i2 <= $mb_len2; $i2 ++) {
			$d [0] [$i2] = $i2;
		}
		
		for($i1 = 1; $i1 <= $mb_len1; $i1 ++) {
			for($i2 = 1; $i2 <= $mb_len2; $i2 ++) {
				// $cost = ($str1[$i1 - 1] == $str2[$i2 - 1]) ? 0 : 1;
				if ($mb_str1 [$i1 - 1] === $mb_str2 [$i2 - 1]) {
					$cost = 0;
					$count_same_letter ++;
				} else {
					$cost = $costReplace; // 替换
				}
				$d [$i1] [$i2] = min ( $d [$i1 - 1] [$i2] + 1, 				// 插入
				$d [$i1] [$i2 - 1] + 1, 				// 删除
				$d [$i1 - 1] [$i2 - 1] + $cost );
			}
		}
		return $d [$mb_len1] [$mb_len2];
		// return array('distance' => $d[$mb_len1][$mb_len2],
	// 'count_same_letter' => $count_same_letter);
	}
}
