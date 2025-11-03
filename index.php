<?php
$data = file_get_contents("https://emo.lv/weather-api/forecast/?city=cesis,latvia");
$weatherData = json_decode($data, true);

// Dekodē JSON uz PHP masīvu
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Laika Prognoze</title>
        <link rel="stylesheet" href="forecast.css"/>
    </head>

    <body>
        <script>
            function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                document.getElementById('txt').innerHTML =
                h + ":" + m + ":" + s;
                var t = setTimeout(startTime, 500);
            }
            function checkTime(i) {
                if (i < 10) {i = "0" + i};  
                return i;
            }

            document.addEventListener("DOMContentLoaded", function () {
                if (localStorage.getItem("theme") === "dark") {
                    document.body.classList.add("dark-mode");
                }
            });

            function toggleMode() {
                document.body.classList.toggle("dark-mode");
                let theme = document.body.classList.contains("dark-mode") ? "dark" : "light";
                localStorage.setItem("theme", theme);
            }
        </script>
        <script>
            function toggleUnits() {
                let unit = document.getElementById("unit-toggle").value;
                document.cookie = "unit=" + unit + "; path=/"; 
                location.reload(); 
            }
        </script>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedUnit = $_POST["unit"];

            if ($selectedUnit == "celsius_km") {
                echo "You selected: Celsius and Kilometers";
            } elseif ($selectedUnit == "fahrenheit_miles") {
                echo "You selected: Fahrenheit and Miles";
            } else {
                echo "Invalid selection.";
            }
        }
        function calculateArc($percent)
        {
            $angle = 180 * ($percent / 100);
            $x = 50 + 40 * cos(deg2rad(180 - $angle));
            $y = 50 - 40 * sin(deg2rad(180 - $angle));
            return "$x $y";
        }
        function convertTemperature($value, $unit)
        {
            if ($unit === "imperial") {
                return $value * 9 / 5 + 32;
            } else {
                return $value + 0 * 5 / 9;
            }
        }

        function convertDistance($value, $unit)
        {
            if ($unit === "imperial") {
                return $value * 0.61;
            } else {
                return $value / 1.01;
            }
        }

        $unit = isset($_COOKIE["unit"]) ? $_COOKIE["unit"] : "metric";
        $value1 = 75;
        $value2 = 35;
        ?>
            <div class="svarigais">
                <div class="navigacija">
                    <div class="navigacija_izvele">
                        <button type="submit" class="poga">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvOWnyNrquADFZ36BVEPcrLYuyFEBX2H6QHw&s" ></button>
                        <div class="Sky">
                            VTDT Sky
                        </div>
                        <div class="mapbilde">
                            <img src="https://forecast-app-vtdt.vercel.app/images/google-maps.gif" alt="Italian Trulli" style="width:24px, height:24px;">
                        </div>
                        <div class="pilsetatxt">
                            <?php echo $weatherData["city"]["name"] .
                                ", " .
                                $weatherData["city"]["country"]; ?>
                        </div>
                    </div>
                    <div class="navigacija_meklesana">
                        <div class="container">
                            <div class="search-box">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyi_CVTmoL1ITHFxQkfLwvj93hcsgA1Olkhg&s">
                                <input type="text" placeholder="Cesis">
                                <div class="globus">
                                    <img src="https://forecast-app-vtdt.vercel.app/images/worldwide.gif">
                                </div>
                            </div>

                            <div class="theme-toggle" button onclick="toggleMode()">

                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWKUkgMMFtIpWRmuj5xVAq008M4FsDunhSCA&s" alt="Theme">
                                Light</button>
                            </div>
                        </div>
                    </div>
                    <div class="navigacija_iestatijumi">
                        <div class="zvanins">
                            <img src="https://forecast-app-vtdt.vercel.app/images/notification.gif" alt="Italian Trulli">
                        </div>
                        <div class="zobrats">
                            <img src="https://forecast-app-vtdt.vercel.app/images/settings.gif" alt="Italian Trulli">
                        </div>
                    </div>
                </div>
                <div class="bloks">
                    <div class="laikapstaklitxt">
                        Current Weather
                        <div class="laikstxts">

                            <body onload="startTime()">
                                <strong><div id="txt"></div></strong>
                            </body>
                        </div>

                        <div class="bloks_stils">
                            <div class="makonis">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/116.png" style="width:48px, height:48px;">
                            </div>
                            <div class="txt8"> 
                                <?php
                                $temperature = $weatherData["list"][0]["temp"]["day"];
                                echo round(
                                    convertTemperature($temperature, $unit),
                                    1
                                ) . ($unit === "imperial" ? "°F" : "°C");
                                ?>


                            </div>

                            <div class="double">

                                <?php echo $weatherData["list"][0]['weather'][0]["description"]; ?>
                                <div class="overcast">
                                    Feels like:
                                     <?php echo $weatherData["list"][0]['feels_like']["day"]; ?>°C

                                </div>
                                <div class="liekas">

                                </div>
                            </div>
                        </div>
                        <div class="Direction">
                       Current wind direction: 
                            <?php 
                            if ($weatherData["list"][0]["deg"] >= 330 || $weatherData["list"][0]["deg"] <30){
                                echo "North";
                                    }
                            if ($weatherData["list"][0]["deg"] >= 30 && $weatherData["list"][0]["deg"] <60){
                                echo "North East";
                            }
                            if ($weatherData["list"][0]["deg"] >= 60 && $weatherData["list"][0]["deg"] <120){
                                echo "East";
                            }
                            if ($weatherData["list"][0]["deg"] >= 120 && $weatherData["list"][0]["deg"] <150){
                                echo "South East";
                            }
                            if ($weatherData["list"][0]["deg"] >= 150 && $weatherData["list"][0]["deg"] <210){
                                echo "South";
                            }
                            if ($weatherData["list"][0]["deg"] >= 210 && $weatherData["list"][0]["deg"] <240){
                                echo "South West";
                            }
                            if ($weatherData["list"][0]["deg"] >= 240 && $weatherData["list"][0]["deg"] <300){
                                echo "West";
                            }
                            if ($weatherData["list"][0]["deg"] >= 300 && $weatherData["list"][0]["deg"] <330){
                                echo "North West";
                            }
                            ?>

                        </div>
                    </div>
                    <form action="process.php" method="POST">
                        <select id="unit-toggle" onchange="toggleUnits()">
                            <option value="metric" <?= $unit === "metric"
                                ? "selected"
                                : "" ?>>Celsius and Kilometers</option>
                            <option value="imperial" <?= $unit === "imperial"
                                ? "selected"
                                : "" ?>>Fahrenheit and Miles</option>
                        </select>
                    </form>
                </div>
                <div class="kaste1">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/clouds.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Gust
                        </div>
                    </div>
                    <div class="txt1">
                        <?php echo $weatherData["list"][0]['gust']; ?>s
                    </div>
                </div>
                <div class="kaste2">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/wind.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Speed
                        </div>
                    </div>
                    <div class="txt1">
                        <?php
                        $distance = $weatherData["list"][0]['speed'];;
                        echo round(convertDistance($distance, $unit), 1) .
                            ($unit === "imperial" ? " miles" : " km/h");
                        ?>


                    </div>
                </div>
                <div class="kaste3">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/humidity.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Humidity
                        </div>
                    </div>
                    <div class="txt1">
                         <?php echo $weatherData["list"][0]["humidity"]; ?>%
                    </div>
                </div>
                <div class="kaste4">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/vision.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Clouds
                        </div>
                    </div>
                    <div class="txt1">
                        <?php echo $weatherData["list"][0]['clouds']; ?>%
                    </div>
                </div>
                <div class="kaste5">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/air-pump.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Pressure
                        </div>
                    </div>
                    <div class="txt1">
                        <?php echo $weatherData["list"][0]["pressure"]; ?> in
                    </div>
                </div>
                <div class="kaste6">
                    <div class="bloks_stils">
                        <div class="makonis1">
                            <img src="https://forecast-app-vtdt.vercel.app/images/air-pump.gif" style="width:24px, height:24px;">
                        </div>
                        <div class="kvalitatetxt">
                            Pressure
                        </div>
                    </div>
                    <div class="txt1">
                     <?php echo $weatherData["list"][0]["pressure"]; ?>°
                    </div>
                </div>
                <div class="bigkaste">
                    <div class="saulemenes">
                        Sun & Moon Summary
                    </div>
                    <div class="bloks_stils1">
                        <div class="bloksstils2">
                            <div class="makonis2">
                                <img src="https://forecast-app-vtdt.vercel.app/images/sun.gif">
                            </div>
                            <div class="gaisatxt">
                                <div class="gaisatxt1">
                                    Air Quality
                                </div>
                                <div class="txt2">
                                    N/A
                                </div>
                            </div>
                        </div>
                        <div class="bloksstils2">
                            <div class="gaisatxt2">
                                <div class="sunrise">
                                    <img src="https://forecast-app-vtdt.vercel.app/images/field.gif">
                                </div>
                                <div class="sunrisetxt">
                                    Sunrise
                                </div>
                                <div class="laiksizvade">
                                    <?php echo date("H:i", $weatherData['list'][0]['sunrise']); ?> AM
                                </div>
                            </div>
                            <div class="gauge-container">
                                <svg viewBox="0 0 100 50" class="gauge">
                                    <path class="gauge-bg" d="M 10 50 A 40 40 0 0 1 90 50" />
                                    <path class="gauge-fill orange" d="M 10 50 A 40 40 0 0 1 <?php echo calculateArc(
                                        $value1
                                    ); ?>" />
                                </svg>
                            </div>
                            <div class="gaisatxt2">
                                <div class="sunset">
                                    <img src="https://forecast-app-vtdt.vercel.app/images/sunset.gif">
                                </div>
                                <div class="sunsetxt">
                                    Sunset
                                </div>
                                <div class="laiksizvade">
                                    <?php echo date("H:i", $weatherData['list'][0]['sunset']); ?> PM
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bloks_stils3">
                        <div class="bloksstils2">
                            <div class="makonis2">
                                <img src="https://forecast-app-vtdt.vercel.app/images/moon.gif">
                            </div>
                            <div class="gaisatxt">
                                <div class="gaisatxt1">
                                    Air Quality
                                </div>
                                <div class="txt2">
                                    N/A
                                </div>
                            </div>
                        </div>
                        <div class="bloksstils2">
                            <div class="gaisatxt2">
                                <div class="sunrise">
                                    <img src="https://forecast-app-vtdt.vercel.app/images/moon-rise.gif">
                                </div>
                                <div class="sunrisetxt">
                                    Moonrise
                                </div>
                                <div class="moonrisetxt">
                                    N/A
                                </div>
                            </div>
                            <div class="gauge-container">
                                <svg viewBox="0 0 100 50" class="gauge">
                                    <path class="gauge-bg" d="M 10 50 A 40 40 0 0 1 90 50" />
                                    <path class="gauge-fill blue" d="M 10 50 A 40 40 0 0 1 <?php echo calculateArc(
                                        $value2
                                    ); ?>" />
                                </svg>
                            </div>
                            <div class="gaisatxt2">
                                <div class="sunset">
                                    <img src="https://forecast-app-vtdt.vercel.app/images/moon-set.gif">
                                </div>
                                <div class="sunsetxt">
                                    Moonset
                                </div>
                                <div class="moonrisetxt">
                                    N/A
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebloks">
                    <div class="dienaspogas">
                        <div class="dienasname">
                            <button style="margin: 0px ; padding:0px 0px 4px ;" class="button1">Today</button>
                            <button style="margin: 0px 0px 0px 16px; padding:0px 0px 4px;" class="button1">Tomorrow</button>
                            <button style="margin: 0px 0px 0px 16px; padding:0px 0px 4px;" class="button-pressed">10 Days</button>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/116.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Friday
                                </div>
                                <div class="raintxt1">

                                     <?php echo $weatherData["list"][1]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][1]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                   Wind: <?php echo $weatherData["list"][1]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][1]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Saturday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][2]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][2]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][2]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][2]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/night/332.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Sunday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][3]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][3]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][3]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][3]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/116.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Monday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][4]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][4]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][4]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][4]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/119.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Tuesday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][5]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][5]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][5]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][5]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Wednesday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][6]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][6]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][6]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][6]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Thursday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][7]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][7]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][7]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][7]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/116.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Friday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][8]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][8]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][8]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][8]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/116.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Saturday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][9]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][9]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][9]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][9]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="days">
                        <div class="days1">
                            <div class="rain">
                                <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                            </div>
                            <div class="blakusrain">
                                <div class="raintxt">
                                    Sunday
                                </div>
                                <div class="raintxt1">
                                     <?php echo $weatherData["list"][10]['weather'][0]["main"]; ?>
                                </div>
                            </div>
                        </div>
                        <div class="linija">
                        </div>
                        <div class="gradiside">
                            <div class="gradiside1">
                                <div class="gradisidetxt">
                                    <?php
                                    $temperature = $weatherData["list"][10]['temp']["day"];
                                    echo round(
                                        convertTemperature($temperature, $unit),
                                        1
                                    ) . ($unit === "imperial" ? "°F" : "°C");
                                    ?>

                                </div>
                                <div class="graditxt">

                                </div>
                            </div>
                            <div class="blakuswindtxt">
                                <div class="windtxt">
                                    Wind: <?php echo $weatherData["list"][10]['speed']; ?>km/h
                                </div>
                                <div class="humiditytxt">
                                    Humidity: <?php echo $weatherData["list"][10]['humidity']; ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="days">
                      <div class="days1">
                          <div class="rain">
                              <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                          </div>
                          <div class="blakusrain">
                              <div class="raintxt">
                                  Sunday
                              </div>
                              <div class="raintxt1">
                                   <?php echo $weatherData["list"][11]['weather'][0]["main"]; ?>
                              </div>
                          </div>
                      </div>
                      <div class="linija">
                      </div>
                      <div class="gradiside">
                          <div class="gradiside1">
                              <div class="gradisidetxt">
                                  <?php
                                  $temperature = $weatherData["list"][11]['temp']["day"];
                                  echo round(
                                      convertTemperature($temperature, $unit),
                                      1
                                  ) . ($unit === "imperial" ? "°F" : "°C");
                                  ?>

                              </div>
                              <div class="graditxt">

                              </div>
                          </div>
                          <div class="blakuswindtxt">
                              <div class="windtxt">
                                  Wind: <?php echo $weatherData["list"][11]['speed']; ?>km/h
                              </div>
                              <div class="humiditytxt">
                                  Humidity: <?php echo $weatherData["list"][11]['humidity']; ?>%
                              </div>
                          </div>
                      </div>
                  </div>
                    <div class="days">
                          <div class="days1">
                              <div class="rain">
                                  <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                              </div>
                              <div class="blakusrain">
                                  <div class="raintxt">
                                      Sunday
                                  </div>
                                  <div class="raintxt1">
                                       <?php echo $weatherData["list"][12]['weather'][0]["main"]; ?>
                                  </div>
                              </div>
                          </div>
                          <div class="linija">
                          </div>
                          <div class="gradiside">
                              <div class="gradiside1">
                                  <div class="gradisidetxt">
                                      <?php
                                      $temperature = $weatherData["list"][12]['temp']["day"];
                                      echo round(
                                          convertTemperature($temperature, $unit),
                                          1
                                      ) . ($unit === "imperial" ? "°F" : "°C");
                                      ?>

                                  </div>
                                  <div class="graditxt">

                                  </div>
                              </div>
                              <div class="blakuswindtxt">
                                  <div class="windtxt">
                                      Wind: <?php echo $weatherData["list"][12]['speed']; ?>km/h
                                  </div>
                                  <div class="humiditytxt">
                                      Humidity: <?php echo $weatherData["list"][12]['humidity']; ?>%
                                  </div>
                              </div>
                          </div>
                      </div>
                    <div class="days">
                          <div class="days1">
                              <div class="rain">
                                  <img src="https://cdn.weatherapi.com/weather/64x64/day/113.png">
                              </div>
                              <div class="blakusrain">
                                  <div class="raintxt">
                                      Sunday
                                  </div>
                                  <div class="raintxt1">
                                       <?php echo $weatherData["list"][13]['weather'][0]["main"]; ?>
                                  </div>
                              </div>
                          </div>
                          <div class="linija">
                          </div>
                          <div class="gradiside">
                              <div class="gradiside1">
                                  <div class="gradisidetxt">
                                      <?php
                                      $temperature = $weatherData["list"][13]['temp']["day"];
                                      echo round(
                                          convertTemperature($temperature, $unit),
                                          1
                                      ) . ($unit === "imperial" ? "°F" : "°C");
                                      ?>

                                  </div>
                                  <div class="graditxt">

                                  </div>
                              </div>
                              <div class="blakuswindtxt">
                                  <div class="windtxt">
                                      Wind: <?php echo $weatherData["list"][13]['speed']; ?>km/h
                                  </div>
                                  <div class="humiditytxt">
                                      Humidity: <?php echo $weatherData["list"][13]['humidity']; ?>%
                                  </div>
                              </div>
                          </div>
                      </div>
                </div>
    </body>

    </html>