# Sistema de Gestion de Envios Postales - ECOBOL

Sistema web en PHP puro para registrar, rastrear y administrar envios postales de ECOBOL con arquitectura MVC basica, MySQL, Bootstrap 5 y PDO.

## Tecnologias usadas

- PHP puro
- MySQL
- PDO
- HTML5
- CSS3
- Bootstrap 5
- JavaScript puro
- Chart.js
- Apache en XAMPP

## Requisitos

- XAMPP con Apache y MySQL
- PHP 8.1 o superior recomendado
- Base de datos MySQL activa
- Navegador moderno

## Instalacion paso a paso

1. Copia la carpeta del proyecto en `htdocs` de XAMPP.
2. Renombra la carpeta si lo necesitas, por ejemplo `ECOBOL-Tracking-System`.
3. Inicia Apache y MySQL desde XAMPP.
4. Importa `database/schema.sql` en phpMyAdmin.
5. Importa `database/seeds.sql` para cargar datos de prueba.
6. Verifica que el nombre de la base sea `ecobol_tracking`.
7. Abre el sistema desde `http://localhost/CORREOS%20DE%20BOLIVIA/frontend/index.php` o la ruta equivalente de tu carpeta.

## Configuracion de XAMPP

- Apache: habilitado
- MySQL: habilitado
- DocumentRoot: `htdocs`
- Si el proyecto cambia de nombre o ruta, ajusta los enlaces base en `backend/utils/helpers.php` si hace falta.
- Recomendado: habilitar `mod_rewrite` en Apache para usar `.htaccess`.

### .htaccess opcional

Se incluye un archivo `.htaccess` en la raiz del proyecto para:

- redirigir la raiz del proyecto a `frontend/index.php`
- permitir rutas limpias opcionales
- mantener acceso directo a `frontend/assets`

Si no usas `mod_rewrite`, el sistema funciona igual entrando por `frontend/index.php`.

### Manejo de errores runtime

- Errores 403, 404, 419 y 500 se muestran con pagina visual Bootstrap.
- El detalle tecnico se muestra cuando `APP_DEBUG=true` (por defecto activo si no se define).
- En produccion se recomienda definir `APP_DEBUG=false` en variables de entorno de Apache.

## Configuracion de base de datos

El archivo `database/schema.sql` crea:

- `usuarios`
- `sucursales`
- `estados`
- `envios`
- `historial_estados`

Incluye llaves foraneas, indices y restricciones para mantener la integridad.

## Como ejecutar

1. Importa la base de datos.
2. Accede a `frontend/index.php`.
3. Inicia sesion con una cuenta de prueba.
4. Usa el menu lateral para registrar envios, actualizar estados, gestionar usuarios y generar reportes.
5. Para rastreo publico usa la opcion `Rastreo publico` sin iniciar sesion.

## Credenciales de prueba

- Administrador: `admin@ecobol.bo`
- Contraseña: `123456`
- Empleado: `empleado@ecobol.bo`
- Contraseña: `123456`

## Estructura principal

- `backend/config/database.php`: conexion PDO.
- `backend/controllers/`: logica de autenticacion, envios, reportes y dashboard.
- `backend/models/`: acceso a datos por entidad.
- `backend/middleware/`: validacion de sesion y roles.
- `frontend/pages/`: vistas del sistema.
- `frontend/components/`: navbar, sidebar y footer reutilizables.
- `database/`: esquema y datos iniciales.

## Nota

El sistema queda listo para una ejecucion local en XAMPP. Si quieres, puedes crear luego un virtual host para evitar rutas largas.
