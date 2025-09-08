<?php

$data = file_get_contents("https://emo.lv/weather-api/forecast/?city=cesis,latvia");

$weatherData = json_decode($data, true);

$pilseta=$weatherData['city']['name'];
$valsts=$weatherData['city']['country'];
$gradi1=$weatherData['list']['0']['temp']['day'];
$gradi2=$weatherData['list']['0']['temp']['min'];
$gradi3=$weatherData['list']['0']['temp']['max'];
$gradi4=$weatherData['list']['0']['temp']['night'];
$gradi5=$weatherData['list']['0']['temp']['eve'];
$gradi6=$weatherData['list']['0']['temp']['morn'];

require __DIR__ . '/index.view.php';








