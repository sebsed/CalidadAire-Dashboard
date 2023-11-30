# CalidadAire-Dashboard
Desarrollo de dashboard para desplegar información de calidad del aire.
**Desarrollado por:** Sergio Javier Flores y Sebastián Sedano Castañeda.
## Descripción de archivos
`index.php`:
	 Página de inicio del dashboard. Contiene información sobre las mediciones más recientes registradas en la base de datos. Ofrece detalles específicos por contaminante o por sensor. 
	 Incluye semáforo de calidad del aire general con recomendaciones y un mapa con la ubicación de las estaciones medidoras.

`header.html`:
	Barra de navegación superior del dashboard. Incluye los enlaces a cada una de las secciones.

`calculoValorSemaforo.php`:
	Archivo utilizado en `index.php` para obtener valor general de calidad del aire y colorear el semáforo.

`historico.php`:
	Sección de datos históricos del dashboard. Despliegue de gráficas de cada contaminante dentro de un rango de fechas específico.

`analisis.php`:
	Sección de análisis de métricas. Despliegue de gráfica que compara datos de dos contaminantes seleccionados dentro de un rango de fechas específico.

`prediccion.php`:
	Sección de estimación de calidad del aire general de acuerdo a datos más recientes o a datos ingresados. Despliega índice estimado de calidad, recomendaciones y semáforo.

Directorio `css`:
	Incluye los estilos necesarios para la página principal y el despliegue del semáforo en `index.php`y `prediccion.php`.

Directorio `js`:
	Archivo con código necesario para el despliegue del velocímetro en `index.php`.

Directorio `db`:
	Archivos para acceder a la base de datos y desplegar la información de diferente manera para los archivos `index.php`, `historico.php`, `analisis.php` y `prediccion.php`.


