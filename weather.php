<?php
    // --------------------------------- //
    //   Fruela Pérez fécit, A.D. 2020   //
    // --------------------------------- //

    require ( __DIR__  . "/settings.php" );

    $reqURL   = $url . $secret . "/" . $latitud . "," . $longitud . "?units=ca&lang=es";
    $datos    = json_decode ( APIcall ( $reqURL ) );

    $actual   = $datos->currently;

    // echo publicIP () . PHP_EOL;

    switch ( $argv [ 1 ] ) 
    {
        case 'minimo':
            echo formatItem ( "temperature", $actual->temperature ) . ", ";
            echo formatItem ( "summary", $actual->summary );
            echo PHP_EOL;

            break;

        case 'corto':
            echo formatItem ( "temperature", $actual->temperature ) . " * ";
            echo formatItem ( "summary",     $actual->summary )     . " * ";
            echo formatItem ( "pressure",    $actual->pressure )    . " * ";
            echo formatItem ( "windSpeed",   $actual->windBearing ) . " ";
            echo formatItem ( "windBearing", $actual->windBearing ) . " * ";
            echo formatItem ( "humidity",    $actual->humidity );
            
            echo PHP_EOL;

            break;

        case 'resumen':
            echo formatItem ( "temperature",         $actual->temperature ) . PHP_EOL;
            echo formatItem ( "summary",             $actual->summary )     . PHP_EOL;
            echo formatItem ( "apparentTemperature", $actual->temperature ) . PHP_EOL;
            echo formatItem ( "pressure",            $actual->pressure )    . PHP_EOL;
            echo formatItem ( "windSpeed",           $actual->windBearing ) . " ";
            echo formatItem ( "windBearing",         $actual->windBearing ) . PHP_EOL;
            echo formatItem ( "humidity",            $actual->humidity )    . PHP_EOL;

            break;

        default:
            echo "Uso: " . $argv [ 0 ] . " minimo | corto | resumen" . PHP_EOL;
            break;
    }

    function formatItem ( $clave, $valor )
    {
        $retorno = "";

        switch ( $clave ) 
        {
            case 'temperature':

                $retorno = round ( $valor ) . "°C";
                break;

            case 'apparentTemperature':
                $retorno = "Sensación térmica: " . round ( $valor ) . "°C";
                break;

            case 'precipProbability':
                $retorno = "Probabilidad de precipitaciones: " . round ( 100 * $valor ) . "%";
                break;

            case 'pressure':
                $retorno = "Presión: " . round ( $valor ) . " hPa (" . hPa2mmHg ( $valor ) . " mmHg)";
                break;

            case 'humidity':
                $retorno = "Humedad relativa: " . round ( 100 * $valor ) . "%";
                break;

            case 'windSpeed':
                $retorno = "Viento: " .  $valor . " km/h";
                break;

            case 'windBearing':
                $retorno = rumbo ( $valor );
                break;

            case 'summary':
                $retorno = $valor;
                break;

            default:
                $retorno = "";
                break;
        }

        return $retorno;
    }

    function hPa2mmHg ( $hpa )
    {
        // 1 hPa = 0.75006375541921 mmHg

        return round ( $hpa * 0.75006375541921 );
    }

    function rumbo ( $grados ) 
    {
        $winddir [] = "N";
        $winddir [] = "NNE";
        $winddir [] = "NE";
        $winddir [] = "ENE";
        $winddir [] = "E";
        $winddir [] = "ESE";
        $winddir [] = "SE";
        $winddir [] = "SSE";
        $winddir [] = "S";
        $winddir [] = "SSO";
        $winddir [] = "SO";
        $winddir [] = "OSO";
        $winddir [] = "O";
        $winddir [] = "ONO";
        $winddir [] = "NO";
        $winddir [] = "NNO";
        $winddir [] = "N";
        
        return $winddir [round ( $grados*16/360 ) ];
    }

    function APIcall ( $url )
    {
        // API Doc:
        // https://darksky.net/dev/docs        
        
        $httpHeaders = array 
        ( 
            "accept: application/json", 
            "cache-control: no-cache", 
            "content-type: application/json" 
        );

        $curl = curl_init ();

        curl_setopt_array
        (
            $curl, 
            array
            (
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => $httpHeaders
            )
        );

        $response = curl_exec  ( $curl );
        $err      = curl_error ( $curl );

        curl_close ( $curl );

        if ( $err ) 
        {
            die ( "cURL Error #:" . $err . PHP_EOL );
        } 
        else 
        {
            return $response;
        }
    }

    function publicIP ()
    {
        return file_get_contents ( 'https://bot.whatismyipaddress.com/' );
    }

?>