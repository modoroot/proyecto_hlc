// Sin implementar
const nombre = document.querySelector('#nombre');
const numero = document.querySelector('#numero');
const btnAgregar = document.querySelector('#btnAgregar');

btnAgregar.addEventListener('click', () => {
    // Comillas inversas
    window.location.href = `agregar/${nombre.value}/${numero.value}`;
})
