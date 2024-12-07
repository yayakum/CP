


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
                   