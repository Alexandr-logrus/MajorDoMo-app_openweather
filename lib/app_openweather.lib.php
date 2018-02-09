<?php

   /**
    * Get wind direction name by direction in degree 
    * @param mixed $degree Wind degree
    * @return string
    */
function getWindDirection($degree)
   {
	$windDirection = array('<#LANG_N#>', '<#LANG_NNE#>', '<#LANG_NE#>', '<#LANG_ENE#>', '<#LANG_E#>', '<#LANG_ESE#>', '<#LANG_SE#>', '<#LANG_SSE#>', '<#LANG_S#>', '<#LANG_SSW#>', '<#LANG_SW#>', '<#LANG_WSW#>', '<#LANG_W#>', '<#LANG_WNW#>', '<#LANG_NW#>', '<#LANG_NNW#>', '<#LANG_N#>');
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
function ConvertPressure($pressure, $from, $to, $precision = 2)
   {
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