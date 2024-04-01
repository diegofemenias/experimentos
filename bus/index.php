<?php

namespace League\OAuth2\Client\Provider;


require './vendor/autoload.php';
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId' => '35b548eb',
    // The client ID assigned to you by the provider
    'clientSecret' => '199d765b7fa401ed9b9ba0b4c76e52e1',
    // The client password assigned to you by the provider
    'redirectUri' => 'https://my.example.com/your-redirect-url/',
    'urlAuthorize' => 'https://mvdapi-auth.montevideo.gub.uy/auth/realms/pci/protocol/openid-connect/token',
    'urlAccessToken' => 'https://mvdapi-auth.montevideo.gub.uy/auth/realms/pci/protocol/openid-connect/token',
    'urlResourceOwnerDetails' => 'https://service.example.com/resource'
]);

// Produccion o development
$prod = true;

try {
    if ($prod) {
        // Token
        $inicioToken = microtime(true);
        $accessToken = $provider->getAccessToken('client_credentials');
        $finToken = microtime(true);
        echo "Tiempo carga Token: " . ($finToken - $inicioToken);


        // Datos
        $api_url = 'https://api.montevideo.gub.uy/api/transportepublico/buses?lines=64%2C21%2C407';
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => "Authorization: Bearer " . $accessToken,
                ),
            )
        );
        $inicioApi = microtime(true);
        $result = file_get_contents($api_url, false, $context);
        $finApi = microtime(true);
        echo "<br>Tiempo carga API: " . ($finApi - $inicioApi);


    } else {
        $inicioApi = microtime(true);
        $result = file_get_contents("ejemplos/allBuses.json", false);
        $finApi = microtime(true);
        echo "<br>Tiempo carga API: " . ($finApi - $inicioApi);
    }

    $inicioJSON = microtime(true);
    $json = json_decode($result);
    $finJSON = microtime(true);
    echo "<br>Tiempo JSON decode: " . ($finJSON - $inicioJSON);


} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

    // Failed to get the access token
    exit($e->getMessage());

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buses</title>
    <link rel="stylesheet" href="css/leaflet.css" />
    <script src="js/leaflet.js"></script>
</head>

<body>
    <div id="mapa" style="width:900px; height:580px;"></div>

    <script>
        var mapOptions = {
            center: [-34.88, -56.14],
            zoom: 14
        }
        // Creating a map object
        var map = new L.map("mapa", mapOptions);
        let locations = [
        <?php
        $i = 0;

        foreach ($json as $bus) {
            $count = $i++;
            $date=date_create($bus->timestamp);
            $hora = date_format($date,"H:i");
            $datos = $bus->company . " " . $bus->line . " " . $bus->origin . " " . $bus->destination . "<br>" . $hora;
            $lat = round($bus->location->coordinates[1], 3);
            $lng = round($bus->location->coordinates[0], 3);
        ?>

            { 

                "id": <?php echo $count; ?>,
                "lat": <?php echo $lat; ?>,
                "long": <?php echo $lng; ?>,
                "title": "<?php echo $datos; ?>",
            },
            <?php 
            }
            ?>
        ];


        for (let i = 0; i < locations.length; i++) {
            new L.Marker([locations[i]['lat'], locations[i]['long']]).addTo(map)
            .bindPopup('<div class="card"><h3>' + locations[i]['title'] +'</h3></div>').openPopup();
            }
        
        // Creating a Layer object
       var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        // Adding layer to the map
        map.addLayer(layer);
    </script>
    <?php echo "Hay " . $i . " buses";
    ?>
</body>

</html>