<?php
include_once 'includes/submit_report.php';

$host = "localhost";
$username = "root";
$password = "root";
$dbname = "pest_map";

$conn = new mysqli($host, $username, $password, $dbname);
$sql1 = "SELECT * FROM pest_tbl;";
$result = mysqli_query($conn, $sql1);
$resultCheck = mysqli_num_rows($result);
?>
            
    <!DOCTYPE html>
    <html>
    <head>
        <title>Harta Epidemiei</title>
        <style>
            .hide{
            display: none;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 1em 0;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        #hero {
            background-image: url('agriculture.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 20px;
            color: white;
            text-align: center;
        }

        #hero h2 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }

        #hero p {
            font-size: 1.2em;
            margin-bottom: 1.5em;
        }

        .cta-button {
            background-color: #fff;
            color: #4CAF50;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
        }

        .cta-button:hover {
            background-color: #e0e0e0;
        }

        #features {
            padding: 50px 20px;
            background-color: white;
            text-align: center;
        }

        #features h2 {
            font-size: 2em;
            margin-bottom: 1em;
        }

        #features ul {
            list-style-type: none;
            padding: 0;
        }

        #features ul li {
            font-size: 1.2em;
            margin: 10px 0;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        footer p {
            margin: 5px 0;
        }

        footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

                    #map {
                        height: 600px;
                    }
                </style>
            </head>
            <body>
                <header>
                    <h1>Harta Epidemiei</h1>
                    <nav>
                        <ul>
                            <li><a href="index.html">Acasă</a></li>
                            <li><a href="report.html">Raportează un Dăunător</a></li>
                            <li><a href="map.html">Hartă</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </header>
                <div id="map"></div>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0nqyY0gf4-4yxWV0gcAOSB1Yz_iu5Kks&callback=initMap" async defer></script>
                <script>
                    function initMap() {
                        // Create a map object and specify the DOM element for display
                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: 45.9432, lng: 24.9668}, // Set the center to Romania's coordinates
                            zoom: 8
                        });

                        
                        <?php

                        if ($resultCheck > 0) {
                            echo "var markers = [";
                        }
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "{lat: ".$row['latitude'].", lng: ".$row['longitude']."},";
                                }
                            }

                        if($resultCheck >0){
                            echo "];";
                        }

                        ?>

                        markers.forEach(function(marker){
                            var googleMarker = new google.maps.Marker({
                                position: {
                                    lat: marker.lat,
                                    lng: marker.lng,
                                },
                                map,
                                title: marker.name
                            })
                        });
                        // Get the user's current location
                        navigator.geolocation.getCurrentPosition(position => {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;

                            // Fetch weather data from OpenWeatherMap API using the user's coordinates
                            fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=c727077b38f64754abbb2779b6920f9d`)
                                .then(response => response.json())
                                .then(data => {
                                    // Get wind speed and direction from the weather data
                                    const windSpeed = data.wind.speed;
                                    const windDirection = data.wind.deg;

                                    // Create a wind layer on the map
                                    const windLayer = new google.maps.weather.WeatherLayer({
                                        temperatureUnits: google.maps.weather.TemperatureUnit.CELSIUS,
                                        windSpeedUnit: google.maps.weather.WindSpeedUnit.KILOMETERS_PER_HOUR,
                                        windOverlay: {
                                            control: google.maps.weather.WindControl.DISPLAY,
                                            color: '#3366FF',
                                            opacity: 0.8
                                        }
                                    });

                                    // Set the wind speed and direction on the wind layer
                                    windLayer.setWind(windSpeed, windDirection);

                                    // Add the wind layer to the map
                                    windLayer.setMap(map);
                                })
                                .catch(error => {
                                    console.error('Error fetching weather data:', error);
                                });
                        });
                    }
                    
                </script>
                <script>

                </script>
                <style>
                    #map {
                        height: 100vh; /* Set the height to 100% of the viewport height */
                        width: 100%; /* Set the width to 100% of the parent container */
                    }
                </style>
            </body>
            </html>
