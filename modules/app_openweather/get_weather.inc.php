<?php
		if (!isset($cityID)) return null;
		$apiKey = gg('ow_setting.api_key');
		$api_method =gg('ow_setting.api_method'); 
		$unit = 'metric';
 		$cnt = gg('ow_setting.forecast_interval');
		$round=intval(gg('ow_setting.ow_round'));
		
		$ret=0;
		while($ret<=4) {
			$query = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityID . "&mode=json&units=" . $unit . "&lang=ru&appid=" . $apiKey;
			$data =  getURL($query);

			//file_put_contents('/var/www/html/texts/Weather.txt', date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
			//file_put_contents('/var/www/html/texts/Weather.txt', $data . PHP_EOL, FILE_APPEND);
		
			$curWeather = json_decode($data);
			if ($curWeather->cod == "404" || $curWeather->cod == "500") {
				$err_msg=$weather->message;
				sleep (15);
			} else {
				$err_msg='';
				$ret=5;
			}
			$ret++;
		}
		if ($err_msg){
			DebMes('OpenWeather: '.$err_msg);
			return;				
		}
		 
		if($curWeather!=false && !empty($curWeather)) {
		  $fact = $curWeather->main;
		  
		  $date = date("d.m.Y G:i:s T Y", $curWeather->dt);
		 
		  sg('ow_fact.temperature', round($fact->temp, $round));
		  sg('ow_fact.weather_type', $curWeather->weather[0]->description);
		  sg('ow_fact.wind_direction', round($curWeather->wind->deg, $round));
		  sg('ow_fact.wind_speed',round($curWeather->wind->speed, $round));
		  sg('ow_fact.humidity', round($fact->humidity, $round));
		  sg('ow_fact.pressure', round($fact->pressure, $round));
		  sg('ow_fact.pressure_mmhg', round(ConvertPressure($fact->pressure, "hpa", "mmhg", 2), $round));
		  sg('ow_fact.image', $curWeather->weather[0]->icon);
		  sg('ow_fact.clouds', $curWeather->clouds->all);
		  sg('ow_fact.rain', isset($fact->rain) ? $fact->rain : '');
		  sg('ow_fact.condCode', $curWeather->weather[0]->id);
		  sg('ow_city.data_update', $date);
		  
		}
		
		
		
	if($api_method=='16d') {
		$query= "http://api.openweathermap.org/data/2.5/forecast/daily?id=" . $cityID . "&mode=json&units=" . $unit . "&cnt=" . $cnt . "&lang=ru" . "&appid=" . $apiKey;
		$data = getURL($query);
		$weather = json_decode($data);
      if ($weather->cod == "404")
      {
         DebMes('OpenWeather: '.$weather->message);
         return;
      }
	  if($weather!=false && !empty($weather)) {
		  $i = 0;
		  foreach($weather->list as $day)
		  {
			 $date = date("d.m.Y", $day->dt);
			 sg('ow_day'.$i.'.date', $date);
			 
			 sg('ow_day'.$i.'.temperature', round(app_openweather::GetCurrTemp($day->temp), $round));
			 sg('ow_day'.$i.'.temp_morn', round($day->temp->morn, $round));
			 sg('ow_day'.$i.'.temp_day', round($day->temp->day, $round));
			 sg('ow_day'.$i.'.temp_eve', round($day->temp->eve, $round));
			 sg('ow_day'.$i.'.temp_night', round($day->temp->night,$round));
			 sg('ow_day'.$i.'.temp_min', round($day->temp->min, $round));
			 sg('ow_day'.$i.'.temp_max', round($day->temp->max, $round));
			 
			 sg('ow_day'.$i.'.weather_type', $day->weather[0]->description);
			 sg('ow_day'.$i.'.wind_direction', round($day->deg, $round));
			 sg('ow_day'.$i.'.wind_speed', round($day->speed, $round));
			 if($day->humidity) sg('ow_day'.$i.'.humidity', round($day->humidity, $round));
			 sg('ow_day'.$i.'.pressure', round($day->pressure, $round));
			 sg('ow_day'.$i.'.pressure_mmhg', round(ConvertPressure($day->pressure, "hpa", "mmhg", 2), $round));
			 sg('ow_day'.$i.'.image', $day->weather[0]->icon);
			 sg('ow_day'.$i.'.clouds', $day->clouds);
			 sg('ow_day'.$i.'.rain', isset($day->rain) ? $day->rain : 0);
			 sg('ow_day'.$i.'.snow', isset($day->snow) ? $day->snow : 0);
			 sg('ow_day'.$i.'.condCode', $day->weather[0]->id);
			 
			 $i++;
		  }
	  }
	} else {
			$query= "http://api.openweathermap.org/data/2.5/forecast?id=" . $cityID . "&mode=json&units=" . $unit . "&cnt=" . $cnt . "&lang=ru" . "&appid=" . $apiKey;
			$data = getURL($query);
			$weather = json_decode($data);
			if ($weather->cod == "404")
			  {
				 DebMes('OpenWeather: '.$weather->message);
				 return;
			  }
	  if($weather!=false && !empty($weather)) {
		  $i = 0;
		  foreach($weather->list as $day)
		  {
			 $date = date("d.m.Y (H:i)", $day->dt);
			 sg('ow_day'.$i.'.date', $date);
			 
			 sg('ow_day'.$i.'.temperature', round($day->main->temp, $round));
			 sg('ow_day'.$i.'.temp_morn', 'na');
			 sg('ow_day'.$i.'.temp_day', 'na');
			 sg('ow_day'.$i.'.temp_eve', 'na');
			 sg('ow_day'.$i.'.temp_night', 'na');
			 sg('ow_day'.$i.'.temp_min', round($day->main->temp_min, $round));
			 sg('ow_day'.$i.'.temp_max', round($day->main->temp_max, $round));
			 
			 sg('ow_day'.$i.'.weather_type', $day->weather[0]->description);
			 sg('ow_day'.$i.'.wind_direction', round($day->wind->deg, $round));
			 sg('ow_day'.$i.'.wind_speed', round($day->wind->speed, $round));
			 if($day->main->humidity) sg('ow_day'.$i.'.humidity', round($day->main->humidity, $round));
			 sg('ow_day'.$i.'.pressure', round($day->main->pressure, $round));
			 sg('ow_day'.$i.'.pressure_mmhg', round(ConvertPressure($day->main->pressure, "hpa", "mmhg", 2), $round));
			 sg('ow_day'.$i.'.image', $day->weather[0]->icon);
			 sg('ow_day'.$i.'.clouds', $day->clouds->all);
			 //sg('ow_day'.$i.'.rain', isset($day->rain->3h) ? $day->rain->3h : 0);
			 //sg('ow_day'.$i.'.snow', isset($day->snow->3h) ? $day->snow->3h : 0);
			 sg('ow_day'.$i.'.condCode', $day->weather[0]->id);

			 $i++;
		  }
	  }
	}
?>