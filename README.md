## 📦 FlexiReserve - Backend (Symfony)

Permite a los usuarios registrarse, iniciar sesión y gestionar reservas de espacios.

## ✅ Requisitos

- PHP >= 8.1
- Composer
- MySQL
- Symfony CLI


##  🚀 Instalación

Clona el repositorio e instala las dependencias de Composer:

- git clone https://github.com/EmilyLino/flexi-reserve-backend.git

- cd flexi-reserve

- composer install


## 📟 Configuración de la Base de Datos
La configuración de la base de datos se realiza en el archivo de entorno .env en la raíz del proyecto.

- Copia el archivo .env a .env.local para tus configuraciones locales
- Crea la base de datos con el nombre: **flexireserve**
- Configura tu conexión a la base de datos:

MySQL:

DATABASE_URL="mysql://root:@127.0.0.1:3306/flexireserve?serverVersion=mariadb-10.4.24"


## ➕ Migraciones de Base de Datos
Una vez configurada la base de datos, ejecuta las migraciones para crear la estructura de tablas:

- php bin/console doctrine:migrations:migrate


## 👨‍💻 Ejecución del Servidor
Puedes iniciar el servidor web integrado de Symfony:

symfony server:start

## 🔎 Documentación de la API

Puedes acceder a la documentación en tu navegador una vez que el servidor esté corriendo en la siguiente ruta:

http://localhost:8000/doc
