<?php

/**
* Get wind direction name by direction in degree 
* @param mixed $degree Wind degree
* @return string
*/
function getWindDirection($degree, $full=false) {
	if (SETTINGS_SITE_LANGUAGE && file_exists(ROOT . 'languages/' . 'app_openweather_' . SETTINGS_SITE_LANGUAGE . '.php')) {
		include_once (ROOT . 'languages/' . 'app_openweather_' . SETTINGS_SITE_LANGUAGE . '.php');
	} else {
		include_once (ROOT . 'languages/'.'app_openweather_default.php');
	}
	if($full) {
		//$windDirection = array(LANG_OW_WIND_FULL_N,LANG_OW_WIND_FULL_NNE,LANG_OW_WIND_FULL_NE,LANG_OW_WIND_FULL_ENE,LANG_OW_WIND_FULL_E,LANG_OW_WIND_FULL_ESE,LANG_OW_WIND_FULL_SE,LANG_OW_WIND_FULL_SSE,LANG_OW_WIND_FULL_S,LANG_OW_WIND_FULL_SSW,LANG_OW_WIND_FULL_SW,LANG_OW_WIND_FULL_WSW,LANG_OW_WIND_FULL_W,LANG_OW_WIND_FULL_WNW,LANG_OW_WIND_FULL_NW,LANG_OW_WIND_FULL_NNW,LANG_OW_WIND_FULL_N);
		$windDirection = array('северный','северо-северо-восточный','северо-восточный','восточно-северо-восточный','восточный','восточно-юго-восточный','юго-восточный','юго-юго-восточный','южный','юго-юго-западный','юго-западный','западно-юго-западный','западный','западно-северо-западный','северо-западный','северо-северо-западный','северный');		
	} else {
		$windDirection = array(LANG_N,LANG_NNE,LANG_NE,LANG_ENE,LANG_E,LANG_ESE,LANG_SE,LANG_SSE,LANG_S,LANG_SSW,LANG_SW,LANG_WSW,LANG_W,LANG_WNW,LANG_NW,LANG_NNW,LANG_N);
	}
	$direction = $windDirection[round(intval($degree) / 22.5)];
	return $direction;
}

/**
* Convert Pressure from one system to another. 
* If error or system not found then function return current pressure.
* @param $vPressure 
* @param $vFrom
* @param $vTo
* @param $vPrecision
* @return
*/
function ConvertPressure($pressure, $from, $to, $precision = 2) {
	if (empty($from) || empty($to) || empty($pressure))
		return $pressure;

	if (!is_numeric($pressure))
		return $pressure;

	$pressure = (float) $pressure;
	$from     = strtolower($from);
	$to       = strtolower($to);

	if ($from == "hpa" && $to == "mmhg")
		return round($pressure * 0.75006375541921, $precision);

	if ($from == "mmhg" && $to == "hpa")
		return round($pressure * 1.33322, $precision);

	return $pressure;
}
?>
