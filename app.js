// Importamos las librerías necesarias
const express = require('express');
const app = express();
const { body, validationResult } = require('express-validator');

// Configuramos el middleware para analizar el cuerpo de las solicitudes HTTP
app.use(express.json());

// Configuramos la plantilla de vistas que utilizaremos
app.set('view engine', 'ejs');

// Configuramos el middleware para analizar los datos enviados en formularios HTML
app.use(express.urlencoded({ extended: true }));

// Configuramos la ruta para la página de registro de usuario, que muestra el formulario HTML
app.get('/registro_usuario', (req, res) => {
  res.render('registro_usuario');
});

// Configuramos la ruta para procesar las solicitudes POST del formulario
app.post('/registrar', [
  // Agregamos validaciones para los campos del formulario utilizando el módulo express-validator
  body('nombre', 'El nombre debe tener al menos 5 caracteres').exists().isLength({ min: 5 }),
  body('usuario', 'El usuario debe tener al menos 3 caracteres').exists().isLength({ min: 5 }),
  body('password', 'La contraseña debe tener al menos 6 caracteres').exists().isLength({ min: 6 }),
  body('confirmarpassword', 'Las contraseñas no coinciden').custom((value, { req }) => {
    if (value != req.body.password) {
      throw new Error('Las contraseñas no coinciden');
    }
    return true;
  }),
  body('imagen', 'La imagen debe ser un archivo JPG o PNG').exists().custom((value, { req }) => {
    if (value.mimetype != 'image/jpg' && value.mimetype != 'image/png') {
      throw new Error('La imagen debe ser un archivo JPG o PNG');
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
    res.render('registro_usuario', { validaciones:validaciones, valores:valores});
  } else {
    // Si los datos son válidos, enviamos una respuesta con un mensaje de éxito
    res.send('Registro completado');
  }

});

// Iniciamos el servidor en el puerto 3000 y mostramos un mensaje en la consola para indicar que el servidor está listo
app.listen(3000, () => {
  console.log('Servidor iniciado en el puerto 3000 (http://localhost:3000/registro_usuario)');
});
