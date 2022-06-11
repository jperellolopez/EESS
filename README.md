# EESS
Código del proyecto sobre precios en estaciones de servicio y almacenamiento de facturas

Desplegado en: https://www.dawsonferrer.com/jperello/EESS/controllers/general_map.php

- Instrucciones para desplegar en local:

1. Tener Apache y MySQL instalados y ejecutándose, por ejemplo con Xampp.
2. Descargar el zip, descomprimir, renombar la carpeta a "EESS" y moverla a la ruta de despliegue (por ejemplo C:\xampp\htdocs ó /var/www/html).
3. Ejecutar el script "bbdd_script.sql" ubicado en EESS/config para crear la base de datos.
4. Modificar las variables "$username" y "$password" en EESS/config/database.php para que coincidan con la de su conexión.
5. Si se despliega en una ruta diferente a "http://localhost/EESS/", modificar las variables "$home_url" en EESS/config/core.php y "url" en EESS/js/invoice_map.js para que coincidan con la ruta de despliegue.
6. Descargar miniRelay en https://netvicious.com/miniRelay/miniRelay.zip para poder utilizar las funcionalidades que utilizan el email. Descomprimir y ejecutar el .exe
7. Ingresar en: http://localhost/EESS/controllers/general_map.php
