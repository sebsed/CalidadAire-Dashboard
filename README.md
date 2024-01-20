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

## index.php
1. Primera sección PHP (1-118) comprende las subsecciones de:
a) Conectarse a la base de datos usando el archivo `database.php` y hacer una consulta para tener el ultimo registro del clima.
b) Pasar los valores de la base de datos a variables PHP
c) Declarar los valores máximos de cada contaminante (para un posterior calculo) y también iniciar las variables del semáforo.
d) Si se recibe una petición POST:
	D1) Buscar que botón se presiono y llamar a la función establecer velocímetro, con el fin de poner los datos en el semáforo.
	D2) Si no se presiono ningún botón, se pone la medición mas alta usando la función arsort para buscar que valor es mas grande y después de usar la misma función de establecervelocimetro.
e) De los datos de sql, se extrae la hora con el fin de acompañar al velocímetro.

2. Segunda sección PHP (120-124) comprende solo el llamado a `calculoValorSemaforo.php` que es un archivo externo encargado que:
a) Obtiene el valor promedio de 4 variables en la base de datos.
b) Normaliza esos valores para estar en la misma escala.
c) Se usa una función PHP para obtener el valor mas grande.
d) En base a cual es mas grande, se determina el banner a mostrar y el color del semáforo (se siguen 4 escalas de valores que dependen de cada contaminante).

3. Sección general de código HTML donde se usan herramientas como:
a) Tablas pequeñas.
b) Acordeones.
c) Card-bodys.
d) Rows y cols para generar el layout de la pagina y mostrar cada sección de la misma.

4. Primera sección de JS el cual en base a los valores de valorsemaforo (calculado en php), indica que advertencias de salud deben mostrarse

5. Segunda sección de JS donde en base a la partícula seleccionada por el usuario, se muestran las indicaciones de salud. 

6. Tercera sección de JS donde se hacen cosas variadas como definir cada cuanto se refresca la pagina, las ventanas emergentes y que símbolos se muestran en el velocímetro.

7. Ultima sección JS la cual es un import del `velocimetro.js`, un código que se basa en el siguiente: https://codepen.io/abhay-111/pen/JjmYzGK Solo se hicieron modificaciones para que el loop no sea eterno y haga uso de los datos proporcionados por la base de datos propia.

