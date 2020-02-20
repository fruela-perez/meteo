<?php
    require ( __DIR__  . "/settings.php" );

    $ret = CallAPI ( "POST", $url, $param );

    $datos = json_decode ( $ret );

    foreach ( $datos->main as $key => $value ) 
    {
        echo formatItem ( $key, $value ) . PHP_EOL; 
    }
    echo PHP_EOL;

    function formatItem ( $clave, $valor )
    {
        $retorno = "";

        switch ( $clave ) 
        {
            case 'temp':

                $retorno = "Temperatura: " . negrita ( round ( $valor ) . "°C" );
                break;

            case 'feels_like':
                $retorno = "Sensación térmica: " . negrita ( round ( $valor ) . "°C" );
                break;

            case 'temp_min':
                $retorno = "Mínima: " . negrita ( round ( $valor ) . "°C" );
                break;

            case 'temp_max':
                $retorno = "Máxima: " . negrita ( round ( $valor ) . "°C" );
                break;

            case 'pressure':
                $retorno = "Presión: " . $valor . " hPa (" . hPa2mmHg ( $valor ) . " mmHg)";
                break;

            case 'humidity':
                $retorno = "Humedad relativa: " . negrita (  $valor . "%" );
                break;

            default:
                $retorno = $clave . ": " . negrita ( $valor );
                break;
        }

        return $retorno;
    }

    function hPa2mmHg ( $hpa )
    {
        // 1 hPa = 0.75006375541921 mmHg

        return round ( $hpa * 0.75006375541921 );
    }

    function negrita ( $cadena )
    {
        return "${bold}" . $cadena . "${normal}";
    }

    function CallAPI ($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;

            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;

            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
?>