# ENTREGA 5.2 HLC
He usado como framework para la validación de un formulario Express. He usado como
formulario la parte del registro de usuario a la base de datos, incluyendo subida de imágenes.

Notas importantes:
 - Credenciales db y nombre db => ```root:root@localhost:3306/proyecto_hlc```
 - Para iniciar el servidor de Express es necesario escribir en la consola ``` nodemon app ``` dentro del directorio padre del proyecto
 - Para acceder a la página de registro es necesario escribir en la barra de direcciones ``` http://localhost:3000/registro_usuario ```
 - Se ha cambiado el directorio de subida de imágenes a ```public/uploads``` (el anterior era ```img_db/```)
 - El formulario se encuentra en la carpeta ``` views/registro_usuario.ejs ```
  
# ENTREGA 6.1 HLC
## Explicación de la entrega
### Framework utilizado
He usado como framework para crear un componente de gestor de contactos Express. Tiene completa funcionalidad con una base de datos MySQL: añade, elimina, y muestra en una tabla los contactos previamente añadidos. Para este componente, necesité añadir unas dependencias más. Añadí las dependencias vía npm: ``` express, mysql, serve-favicon ``` (las demás sólo las utilizo en la anterior entrega).

### Instalación
Para instalar Express, hay que seguir los siguientes pasos:
- En la carpeta padre del proyecto: ``` npm install express ```. Esto instala las dependencias vía Node.js. <i>Previamente habría que tener instalado en el sistema Node.js.</i>
- Se importa al main de nuestro proyecto (en mi caso app.js) => ``` const express = require('express'); ```. Si lo haces con módulos habría que añadir una línea en el archivo package.json ``` type:module ```, y cambiar la línea de importación a ``` import express from ?``` (no me acuerdo exactamente).
- Instanciamos el servidor => ``` const app = express(); ```
- Iniciamos el servidor en el puerto que queramos => ```app.listen(3000, () => {});```
- Nos dirigimos al navegador y escribimos ``` localhost:3000 ```. Nos mostrará <i>Cannot GET /</i>. Eso significa que el servidor se ha iniciado correctamente, pero no existe aún el index.

### Configuración
Para configurarlo de forma básica, debemos introducir las siguientes líneas de código en nuestro main js (en mi caso app.js)
```js
// Registramos EJS como motor de plantillas para las vistas con extensión ".ejs"
app.engine('ejs', require('ejs').__express);
// Configuramos la plantilla de vistas que utilizaremos
app.set('view engine', 'ejs');
```
Para mostrar los contenidos, yo he elegido ejs como motor de vistas, pero existen alternativas como pug.

- Creamos el archivo index.ejs en la carpeta ```views/``` y metemos cualquier contenido html de prueba.
- Luego, en el js main introducimos el siguiente código:
```js
// Configuramos la ruta por defecto, que muestra las rutas disponibles
app.get('/', (req, res) => {
    // Renderizamos la vista index.ejs
    res.render('index');
});
```
Una vez lo hagamos, entrando en el navegador a ```localhost:3000``` nos mostrará el contenido del archivo index.ejs que hemos creado previamente.

### Hoja de estilos CSS
En mi caso, he utilizado el framework CSS de Bootstrap. Para ello, simplemente he importado el CDN de Bootstrap en los archivos ejs que lo necesitaban.
```html
<!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
```