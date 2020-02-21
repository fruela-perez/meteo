# Meteo

Información meteorológica obtenida a través del API de [Dark Sky](https://darksky.net/)

## Uso:  
1. Obtener una KEY API en [Dark Sky](https://darksky.net/)
2. Editar `settings.php`, asignar la clave API a `$secret`y las coordenadas en `$latitud` y `$longitud`.
3. Ejecutar `$ php weather.php { minimo | corto | resumen }`

### Mínimo

```
$ php weather.php minimo

2°C, Despejado
```

### Corto
```
$ php weather.php corto

2°C * Despejado * Presión: 1031 hPa (773 mmHg) * Viento: 41 km/h NE * Humedad relativa: 90%
```
### Resumen

```
$ php weather.php resumen

2°C
Despejado
Sensación térmica: 2°C
Presión: 1031 hPa (773 mmHg)
Viento: 41 km/h NE
Humedad relativa: 90%
```
