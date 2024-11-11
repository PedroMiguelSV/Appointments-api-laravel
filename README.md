# API de Gestión de Citas: appointments-api  

![Appointments API](https://img.shields.io/badge/API-appointments-blue)  
![Laravel](https://img.shields.io/badge/Laravel-11-red)  
![JWT](https://img.shields.io/badge/Auth-JWT-orange)  
![MySQL](https://img.shields.io/badge/Database-MySQL-blue)  

Una API desarrollada en Laravel para la gestión de citas. Esta API permite el manejo de usuarios, clientes, servicios y citas, proporcionando autenticación segura mediante JWT.  

## Tabla de Contenidos  
- [Características](#características)  
- [Requisitos](#requisitos)  
- [Instalación](#instalación)  
- [Configuración](#configuración)  
- [Seeder de Usuario Inicial](#seeder-de-usuario-inicial)  
- [Uso](#uso)  
- [Rutas de la API](#rutas-de-la-api)  
- [Consideraciones de Seguridad](#consideraciones-de-seguridad)  
- [Contribuciones](#contribuciones)  
- [Licencia](#licencia)  

## Características  
- Autenticación de usuarios con JWT.  
- CRUD de usuarios, clientes, servicios y citas.  
- Vista de citas con clientes y servicios.  
- Búsqueda de clientes por nombre o teléfono.  
- Validaciones y mensajes de error personalizados.  

## Requisitos  
- PHP >= 8.0  
- Composer  
- MySQL  
- Laravel >= 11  
- XAMPP o un entorno de servidor similar para desarrollo  

## Instalación  

1. **Clonar el repositorio**:  
   git clone https://github.com/PedroMiguelSV/Appointments-api-laravel.git  
   cd Appointments-api-laravel  

2. **Instalar dependencias**:  
   composer install  

3. **Configurar las variables de entorno**:  
   - Copia el archivo `.env.example` y renómbralo a `.env`.  

   - Configura la conexión a la base de datos:  
     DB_CONNECTION=mysql  
     DB_HOST=127.0.0.1  
     DB_PORT=3306  
     DB_DATABASE=appointmentcalendar  
     DB_USERNAME=tu_usuario  
     DB_PASSWORD=tu_contraseña  

   - Genera la clave de aplicación:  
     php artisan key:generate  

4. **Ejecutar migraciones y seeders**:  
   php artisan migrate --seed  

## Configuración  

### Configuración de JWT  
Asegúrate de agregar una clave secreta en tu archivo `.env`:  
JWT_SECRET=tu_jwt_secret  

Luego genera la clave secreta:  
php artisan jwt:secret  

## Seeder de Usuario Inicial  
Un usuario administrador inicial se crea automáticamente al ejecutar el seeder. Las credenciales predeterminadas son:  
- **Email**: `admin@example.com`  
- **Contraseña**: `password`  

## Uso  

### Iniciar el Servidor  
Inicia el servidor de desarrollo de Laravel:  
php artisan serve  
La API estará disponible en `http://localhost:8000`.  

## Rutas de la API  

### Autenticación  
| Método | Ruta           | Descripción                      |
|--------|-----------------|----------------------------------|
| POST   | `/register`     | Registrar un nuevo usuario      |
| POST   | `/login`        | Iniciar sesión y obtener token  |
| POST   | `/logout`       | Cerrar sesión y anular token    |
| POST   | `/refresh`      | Refrescar token JWT             |
| GET    | `/me`           | Obtener datos del usuario       |

### Gestión de Usuarios  
| Método | Ruta         | Descripción                     |
|--------|--------------|---------------------------------|
| GET    | `/users`     | Listar todos los usuarios       |
| DELETE | `/users/{id}`| Eliminar un usuario específico  |

### Clientes  
| Método | Ruta              | Descripción                     |
|--------|--------------------|---------------------------------|
| GET    | `/clients`        | Listar todos los clientes       |
| POST   | `/clients`        | Crear un nuevo cliente          |
| GET    | `/clients/{id}`    | Mostrar un cliente específico   |
| PUT    | `/clients/{id}`    | Actualizar un cliente           |
| DELETE | `/clients/{id}`    | Eliminar un cliente             |
| GET    | `/clients/search`  | Buscar clientes por nombre o teléfono |

### Servicios  
| Método | Ruta               | Descripción                     |
|--------|---------------------|---------------------------------|
| GET    | `/services`        | Listar todos los servicios      |
| POST   | `/services`        | Crear un nuevo servicio         |
| GET    | `/services/{id}`    | Mostrar un servicio específico  |
| PUT    | `/services/{id}`    | Actualizar un servicio          |
| DELETE | `/services/{id}`    | Eliminar un servicio            |

### Citas  
| Método | Ruta                  | Descripción                       |
|--------|------------------------|-----------------------------------|
| GET    | `/appointments`       | Listar todas las citas            |
| POST   | `/appointments`       | Crear una nueva cita              |
| GET    | `/appointments/view`   | Vista consolidada de citas        |
| GET    | `/appointments/{id}`   | Mostrar una cita específica       |
| PUT    | `/appointments/{id}`   | Actualizar una cita               |
| DELETE | `/appointments/{id}`   | Eliminar una cita                 |

## Consideraciones de Seguridad  
1. **Usuario Administrador**: La aplicación evita eliminar al usuario administrador para asegurar acceso continuo.  
2. **JWT**: Configura la duración del token y el refresco en el archivo `.env` para controlar las sesiones.  

## Contribuciones  
Las contribuciones son bienvenidas. Por favor, abre un issue o envía un pull request para mejorar esta API.  

## Licencia  
Este proyecto está bajo la licencia MIT.  

# 🌐 Guía de Configuración en Postman  

Esta colección te permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) en la API de citas. Sigue estos pasos para configurar y usar la colección y el entorno en Postman.  

## 📋 Requisitos Previos  

1. **Postman**: Asegúrate de tener Postman instalado. Puedes descargarlo desde [aquí](https://www.postman.com/downloads/).  
2. **Archivos de la Colección y Entorno**: Descarga los archivos `appointments-collection.json` y `appointments-environment.json` desde este repositorio.  

## 🚀 Instrucciones de Configuración  

### 1️⃣ Importar la Colección en Postman  

1. Abre Postman.  
2. En la esquina superior izquierda, selecciona **Import**.  
3. Arrastra y suelta el archivo `appointments-collection.json` o selecciona el archivo manualmente.  
4. Postman importará las solicitudes de la API en una colección llamada **Appointment API**.  

### 2️⃣ Importar el Entorno en Postman  

1. Dirígete a la pestaña **Environments** en Postman (menú superior derecho).  
2. Haz clic en **Import** y selecciona `appointments-environment.json`.  
3. Una vez importado, selecciona el entorno `Appointment API` para activarlo.  

### 3️⃣ Configuración Automática del Token de Autenticación  

- La colección incluye un script de autenticación en la solicitud **Login** que guarda el token y su tiempo de expiración en variables de entorno.  
- Para cada solicitud protegida:  
- En la pestaña **Authorization** de cada solicitud, se utiliza el tipo **Bearer Token** con la variable `{{token}}`, asegurando que el token se añada automáticamente.  
- Antes de cada solicitud, un script en **Pre-request Script** verifica si el token está próximo a expirar. Si es necesario, envía una solicitud de refresco del token a `/api/refresh`.  

## 📝 Instrucciones de Uso  

1. **Inicia sesión**: Ejecuta la solicitud **Login** en la carpeta `Auth` y proporciona las credenciales.  
2. **Realiza solicitudes**:  
   - Después de iniciar sesión, puedes ejecutar cualquier solicitud en la colección, y Postman usará automáticamente el token guardado.  
3. **Manejo Automático del Token**:  
   - La colección está configurada para verificar la expiración del token antes de cada solicitud y solicitar un nuevo token si está próximo a expirar.  

## 📂 Organización de la Colección  

La colección está organizada en varias carpetas para facilitar la navegación:  

- **Auth**: Solicitudes relacionadas con la autenticación, como `login`, `register`, `logout`, y `refresh`.  
- **CRUD de Clientes**: Incluye operaciones CRUD para manejar clientes.  
- **CRUD de Servicios**: Incluye operaciones CRUD para manejar servicios.  
- **CRUD de Citas**: Incluye operaciones CRUD para manejar citas.  

## 💡 Tips y Recomendaciones  

- **Verifica el Entorno**: Asegúrate de que el entorno `Appointment API` esté activo para que se utilicen las variables correctamente.  
- **Scripts Personalizados**: La colección utiliza scripts para el manejo automático del token y la expiración, optimizando el flujo de trabajo en Postman.  