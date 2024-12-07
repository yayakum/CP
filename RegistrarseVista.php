<?php
include_once 'apiUsuarios.php';
include 'navbar.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Vincula Bootstrap CSS si usas CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f7fa;
            --text-color: #333;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
        }

        .form-wrapper {
            padding: 40px;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--text-color);
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        input:invalid:not(:focus):not(:placeholder-shown) ~ .invalid-feedback {
            display: block;
        }

        button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3a7bd5;
        }

        @media (max-width: 600px) {
            .form-wrapper {
                padding: 20px;
            }
        }


        .error-message {
            color: red; /* Color del mensaje de error */
            font-size: 12px; /* Tamaño de fuente del mensaje */
            margin-top: 5px; /* Espacio superior */
            font-weight: bold; /* Negrita para mayor visibilidad */
            margin-bottom: 15px;
            text-align: center;
        }

        input.error {
            border: 2px solid red; /* Borde rojo para campos inválidos */
            transition: border-color 0.3s; /* Transición suave para el borde */
        }

        input.valid {
            border: 2px solid green; /* Borde verde para campos válidos */
            transition: border-color 0.3s; /* Transición suave para el borde */
        }

        /* Opcional: estilo para los inputs antes de la interacción */
        input {
            border: 2px solid #ccc; /* Borde neutro para inputs no validados */
            transition: border-color 0.3s; /* Transición suave para el borde */
        }

        /* Agrega un poco de espacio en los inputs */
        input {
            padding: 8px; /* Espaciado interno para los inputs */
            border-radius: 4px; /* Bordes redondeados */
        }
    </style>
  </head>


<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Registro de Usuario</h2>
            
            <form id="registerForm" action="Registrarse.php" method="post" enctype="multipart/form-data" onsubmit= "return ValidarFormulario()" >
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email"  placeholder="ejemplo@correo.com">
                </div>
                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" id="username" name="username" >
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" >
                </div>
                <div class="form-group">
                    <label for="role">Rol de Usuario</label>
                    <select id="role" name="role" >
                        <option value="" disabled selected>Seleccione un rol</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="comprador">Comprador</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="avatar">Imagen Tipo Avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="fullName">Nombre Completo</label>
                    <input type="text" id="fullName" name="fullName"  placeholder="Nombre y Apellidos">
                </div>
                <div class="form-group">
                    <label for="dob">Fecha de Nacimiento</label>
                    <input type="date" id="dob" name="dob" >
                </div>
                <div class="form-group">
                    <label for="gender">Sexo</label>
                    <select id="gender" name="gender" >
                        <option value="" disabled selected>Seleccione un sexo</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <button type="submit">Registrar</button>
            </form>
        </div>
    </div>
    <script>
        


        function ValidarFormulario(){
            const password = document.getElementById('password').value;
            const nombre = document.getElementById('fullName').value;
            const fechaNacimiento = document.getElementById('dob').value;
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            const tipoUsuario = document.getElementById('role').value;
        
            let valid = true;
        
            // Validación de contraseña
            const passwordPattern = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,30}$/;
            if (!passwordPattern.test(password) || password.includes(' ')) {
                mostrarError('password', 'La contraseña debe tener al menos 8 caracteres, incluir una mayúscula, un número y un carácter especial.');
                valid = false;
            } else {
                marcarCampoValido('password');
            }
        
            // Validación de nombre
            const namePattern = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/;
            if (!namePattern.test(nombre)) {
                mostrarError('fullName', 'El nombre debe tener entre 3 y 50 caracteres');
                valid = false;
            } else {
                marcarCampoValido('fullName');
            }
        
            
          
        
            // Validación de fecha de nacimiento
            const currentDate = new Date();
            const minDate = new Date('1950-01-01');
            const birthDate = new Date(fechaNacimiento);
        
            if (!fechaNacimiento || birthDate > currentDate || birthDate <= minDate) {
                mostrarError('dob', 'La fecha de nacimiento no puede ser en el futuro ni anterior a 1950.');
                valid = false;
            } else {
                const edad = currentDate.getFullYear() - birthDate.getFullYear();
                const isInstructor = tipoUsuario === 'vendedor';
                const isAlumno = tipoUsuario === 'comprador';
        
                if ((isInstructor && edad < 21) || (isAlumno && edad < 12)) {
                    mostrarError('dob', `La edad mínima para ${tipoUsuario} es ${isInstructor ? '21 años' : '12 años'}.`);
                    valid = false;
                } else {
                    marcarCampoValido('dob');
                }
            }
        
             // Validación de correo (sin espacios y con dominios permitidos)
             const dominiosPermitidos = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
            if (email.includes(' ') || !email.includes('@')) {
                mostrarError('email', 'El correo electrónico no debe contener espacios y debe tener un formato válido.');
                valid = false;
            } else {
                const partes = email.split('@');
                const dominio = partes[1];
                if (!dominiosPermitidos.includes(dominio)) {
                    mostrarError('email', 'Por favor, usa un correo con dominio válido: gmail.com, hotmail.com, yahoo.com, outlook.com.');
                    valid = false;
                } else {
                    marcarCampoValido('email'); // Marcar como válido
                }
            }
              // Validación de nombre de usuario
          const usuarioPattern = /^[^\s]{3,}$/;  // No permite espacios y requiere al menos 3 caracteres
          if (!usuarioPattern.test(username)) {
              mostrarError('username', 'El nombre de usuario debe tener al menos 3 caracteres y no debe contener espacios.');
              valid = false;
          } else {
              marcarCampoValido('username'); // Marcar como válido
          }
            // Retornar el resultado de la validación
            return valid;
            
        
        
                    function mostrarError(inputId, message) {
                    const inputElement = document.getElementById(inputId);
        
                    // Comprobar si ya hay un mensaje de error
                    if (!inputElement.nextElementSibling || !inputElement.nextElementSibling.classList.contains('error-message')) {
                        const errorElement = document.createElement('div');
                        errorElement.className = 'error-message';
                        errorElement.textContent = message;
                        inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
                        
                        // Resaltar el campo con error
                        inputElement.classList.add('error');
                        inputElement.classList.remove('valid'); // Asegúrate de quitar la clase valid si existe
        
                        // Eliminar el mensaje de error después de 15 segundos
                        setTimeout(() => {
                            errorElement.remove(); // Eliminar el mensaje de error
                            inputElement.classList.remove('error'); // Limpiar la clase error
                        }, 15000);
                    } else {
                        inputElement.classList.add('error'); // Asegúrate de agregar la clase error si ya hay un mensaje
                    }
                    }
        
                    function marcarCampoValido(inputId) {
                    const inputElement = document.getElementById(inputId);
                    inputElement.classList.add('valid'); // Añadir clase valid
                    inputElement.classList.remove('error'); // Asegúrate de quitar la clase error
        
                    // Eliminar el mensaje de error si existe
                    const errorElement = inputElement.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('error-message')) {
                        errorElement.remove(); // Eliminar el mensaje de error
                    }
                    }
                }
                   
    </script>
</body>
</html>