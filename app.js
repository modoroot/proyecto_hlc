// Importamos las librerías necesarias
// Importamos el módulo express para crear el servidor
const express = require('express');
// Importamos el módulo mysql para conectarnos a la base de datos
const mysql = require('mysql');
// Importamos el módulo crypto para encriptar la contraseña
const crypto = require('crypto');
// Importamos el módulo multer para subir archivos
const multer = require('multer');
// Agregar icono a mi sitio web [favicon.ico]
const favicon = require('serve-favicon');

// Variable global para guardar el nombre del archivo a subir al servidor
let uploadedFileName = '';
// Configuramos multer para almacenar los archivos en el directorio public/uploads
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, 'public/uploads')
  },
  filename: function (req, file, cb) {
    // Generamos un nombre único para el archivo a subir al servidor utilizando la fecha actual y un número aleatorio
    const nombreUnico = Date.now() + '-' + Math.round(Math.random() * 1E9)
    // Obtenemos la extensión del archivo a subir al servidor y la concatenamos al nombre del archivo
    const extension = file.mimetype.split('/')[1];
    // Guardamos el nombre del archivo en la variable global uploadedFileName para poder acceder a él desde la función
    // de callback de la consulta SQL
    uploadedFileName = `${file.fieldname}-${nombreUnico}.${extension}`;
    // Enviamos el nombre del archivo a la función de callback de multer para que lo guarde en el servidor
    cb(null, uploadedFileName);
  }
})

// Crear una conexión a la base de datos
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'root',
  database: 'proyecto_hlc'
});
// Conectar a la base de datos
connection.connect((err) => {
  if (err) {
    console.error('Error de conexión: ' + err.stack);
    return;
  }
  console.log('Conexión a la base de datos establecida');
});

// Creamos el objeto multer con la configuración de almacenamiento
const upload = multer({ storage: storage })
// Creamos el servidor
const app = express();
// Importamos el módulo express-validator para validar los datos del formulario
const { body, validationResult } = require('express-validator');

// Configuramos el middleware para analizar el cuerpo de las solicitudes HTTP
app.use(express.json());

// Icono de la web
app.use(favicon(__dirname + '/public/favicon.ico'));
// Registramos EJS como motor de plantillas para las vistas con extensión ".ejs"
app.engine('ejs', require('ejs').__express);

// Configuramos la plantilla de vistas que utilizaremos
app.set('view engine', 'ejs');

// Configuramos el middleware para analizar los datos enviados en formularios HTML
app.use(express.urlencoded({ extended: true }));

// Configuramos el middleware para subir archivos al servidor utilizando multer
app.use(express.static('public'));

// Configuramos el middleware para subir archivos al servidor utilizando multer y lo asignamos al campo imagen del formulario
app.use(upload.single('imagen'));

// Configuramos la ruta por defecto, que muestra las rutas disponibles
app.get('/', (req, res) => {
  // Renderizamos la vista index.ejs
  res.render('index');
});

// Configuramos la ruta para la página de registro de usuario, que muestra el formulario HTML
app.get('/registro_usuario', (req, res) => {
  res.render('registro_usuario');
});

// Ruta para obtener todos los contactos
app.get('/contactos', (req, res) => {
  connection.query('SELECT * FROM contacto', (err, result) => {
    if (err) {
      console.log(err);
    } else {
      res.render('contactos', { registros: result }); // pasar resultado de consulta a la vista
    }
  });
});
// Elimina un contacto por su id
app.post('/eliminar_contacto/:id', (req, res) => {
  // Obtenemos el id del contacto a eliminar
  const id = req.params.id;
  // Ejecutamos la consulta para eliminar el contacto
  connection.query('DELETE FROM contacto WHERE id_contacto = ?', [id], (err, result) => {
    if (err) {
      // Si ocurre un error, lo mostramos en la consola y enviamos un mensaje de error al cliente
      console.log(err);
      res.status(500).send('Error al eliminar el registro');
    } else {
      // Si no ocurre ningún error, mostramos un mensaje en la consola y redirigimos al cliente a la página principal
      console.log(`Se ha eliminado el registro con ID ${id}`);
      res.redirect('/');
    }
  });

});



// Configuramos la ruta para procesar las solicitudes POST del formulario
app.post('/registrar', [
  // Agregamos validaciones para los campos del formulario utilizando el módulo express-validator
  body('nombre')
    .notEmpty().withMessage('Campo nombre: El campo está vacío')
    .isLength({ min: 5 }).withMessage('Campo nombre: Debe tener al menos 5 caracteres')
    .isAlpha().withMessage('Campo nombre: Solo se permiten caracteres alfabéticos'),

  // El campo usuario debe tener al menos 4 caracteres
  body('usuario', 'Campo usuario: debe tener al menos 4 caracteres').exists().isLength({ min: 4 }),

  body('password',)
    .notEmpty().withMessage('Campo contraseña: El campo está vacío')
    .isLength({ min: 6 }).withMessage('Campo contraseña: Debe tener al menos 6 caracteres')
    .matches(/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/).withMessage('Campo contraseña: Debe contener al menos una letra y un número'),
  // El campo confirmarpassword debe tener al menos 6 caracteres
  body('confirmarpassword', 'Las contraseñas no coinciden').custom((value, { req }) => {
    // Comparamos el valor del campo confirmarpassword con el valor del campo password
    if (value != req.body.password) {
      // Si los valores no coinciden, lanzamos un error (no funciona por ahora)
      throw new Error('Las contraseñas no coinciden');
    }
    return true;
  }),
  // El campo imagen debe tener un archivo seleccionado
  body('imagen', 'Debe seleccionar una imagen').custom((value, { req }) => {
    if (uploadedFileName == '') {
      // Si no hay un archivo seleccionado, lanzamos un error (no funciona por ahora)
      throw new Error('Debe seleccionar una imagen');
    }
    return true;
  })

], (req, res) => {
  // Validamos los datos del formulario utilizando el módulo express-validator
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    // Si hay errores, mostramos el formulario de registro con los errores correspondientes
    const valores = req.body
    const validaciones = errors.array()
    res.render('registro_usuario', { validaciones: validaciones, valores: valores });
  } else {
    // Si los datos son válidos, insertamos los datos en la base de datos
    const valores = req.body;
    // Consulta SQL para insertar los datos en la base de datos
    const query = 'INSERT INTO usuario (username, nombre, password, imagen) VALUES (?, ?, ?, ?)';
    // Encriptamos la contraseña utilizando el algoritmo MD5 y la insertamos en la base de datos
    const passwordHash = crypto.createHash('md5').update(valores.password).digest('hex');
    // Ejecutamos la consulta SQL para insertar los datos en la base de datos y mostramos un mensaje en la consola en caso de error
    connection.query(query, [valores.usuario, valores.nombre, passwordHash, uploadedFileName], (error, results, fields) => {
      if (error) {
        // Si hay un error, mostramos un mensaje en la consola y enviamos una respuesta con el error
        console.error('Error al insertar los datos: ' + error.stack);
        return;
      }
      // Mostramos un mensaje en la consola si los datos se insertaron correctamente
      console.log('Datos insertados correctamente => Nombre: ' + valores.nombre + ' | Usuario: ' + valores.usuario);
      // Cerrar la conexión a la base de datos al finalizar la solicitud
      // connection.end((err) => {
      //   if (err) {
      //     console.error('Error al cerrar la conexión: ' + err.stack);
      //     return;
      //   }
      // Redirigimos al usuario al login
      res.redirect('http://localhost:80/project_hlc/login.php');

      //   console.log('Conexión a la base de datos cerrada');
      // });

    });
  }
});
// Configuramos el post para añadir un contacto
app.post('/aniadir_contacto', function (req, res) {
  // Guardamos los datos del formulario en variables
  const nombre = req.body.nombre;
  const telefono = req.body.telefono;
  // Consulta SQL para insertar los datos en la base de datos
  const query = "INSERT INTO contacto (nombre, telefono) VALUES (?, ?)";
  // Ejecutamos la consulta SQL para insertar los datos en la base de datos y mostramos un mensaje en la consola en caso de error
  connection.query(query, [nombre, telefono], function (error, results, fields) {
    if (error) {
      // Si hay un error, mostramos un mensaje en la consola y enviamos una respuesta con el error
      console.error('Error al insertar los datos: ' + error.stack);
      return;
    }
    // Mostramos un mensaje en la consola si los datos se insertaron correctamente
    console.log('Datos insertados correctamente: ' + nombre + ' || ' + telefono);
    // Redirigimos al usuario a la página principal de la aplicación web
    res.redirect('/');
  });
});

// Iniciamos el servidor en el puerto 3000 y mostramos un mensaje en la consola para indicar que el servidor está listo
app.listen(3000, () => {
  console.log('Servidor iniciado en el puerto 3000 (http://localhost:3000)');
});
