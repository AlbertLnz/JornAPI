# API de Jornalia

<div align="center">
  <img src="https://github.com/user-attachments/assets/b6cb3ec7-ce69-4c2e-8bec-1a990e5ece74" alt="jornaliaHD" width="300" height="300">
</div>


## Índice

1. [Descripción del Proyecto](#descripción-del-proyecto)
2. [Tecnologías Utilizadas](#tecnologías-utilizadas)
3. [Estructura del Proyecto](#estructura-del-proyecto)
4. [Instalación y Configuración](#instalación-y-configuración)
5. [Endpoints](#endpoints)

## Descripción del Proyecto

Jornalia nació de la necesidad personal de simplificar y organizar el registro de horas trabajadas en un entorno logístico. En mi trabajo diario, me encontraba apuntando manualmente mis horas y calculando los totales, lo cual no solo era tedioso, sino también propenso a errores. Al darme cuenta de que muchos de mis compañeros enfrentaban el mismo desafío, decidí crear Jornalia, una solución digital para llevar un control más detallado y eficiente de las jornadas laborales.

Jornalia es una API diseñada para optimizar y centralizar el registro de horas trabajadas, así como el cálculo preciso de sueldos. Ya no hace falta depender de papeles o hojas de cálculo que se pueden extraviar; todo está almacenado de manera segura y accesible.

¿Qué hace Jornalia?
Esta plataforma permite:

Registrar el inicio y fin de la jornada laboral de manera precisa, evitando confusiones y errores.
Identificar las horas normales, extras y festivas, garantizando un cálculo justo para el empleado y una gestión transparente para la empresa.
Calcular el sueldo de manera exacta tomando en cuenta las horas trabajadas, incluyendo el pago por horas extras y festivas, ajustado a la tarifa por hora del empleado.
El sistema está diseñado no solo para empleados, sino también para empresas, con el objetivo de optimizar los procesos administrativos. Las organizaciones pueden gestionar horarios variables de manera eficiente, y los empleados pueden estar seguros de que recibirán una compensación justa por su tiempo trabajado.

## Tecnologías Utilizadas

- **Laravel 11**: Framework PHP que facilita el desarrollo de aplicaciones web.
- **PHP 8.2**: Lenguaje de programación para la lógica de backend.
- **MySQL**: Base de datos relacional para almacenar la información de los empleados, horas trabajadas y sueldos.
- **Redis**: Sistema de cache para optimizar el rendimiento de la aplicación y almacenar sesiones.
- **JWT (JSON Web Token)**: Sistema de autenticación para asegurar que las solicitudes a la API sean realizadas por usuarios autorizados.
- **MVC con Capas**: Se sigue el patrón de arquitectura MVC (Modelo-Vista-Controlador) con capas adicionales como:
  - **DTOs (Data Transfer Objects)**: Para la transferencia de datos entre capas de la aplicación.
  - **Services**: Contienen la lógica de negocio de la aplicación.
  - **Traits**: Para reutilizar código entre clases de manera eficiente.
- **Eventos y Colas de Trabajo**: Para procesar el cálculo de sueldos de manera asíncrona, utilizando eventos y colas de trabajo en Laravel.

## Estructura del Proyecto


El proyecto sigue una arquitectura de capas para mantener el código organizado, modular y fácil de mantener. La estructura básica es la siguiente:  

app/ ├── DTOs/ ├── Events/ ├── Jobs/ ├── Listeners/ ├── Models/ ├── Services/ ├── Traits/ ├── Http/ │ ├── Controllers/ │ ├── Requests/ │ └── Middleware/


### Descripción de las Capas

- **DTOs (Data Transfer Objects)**:  
  Se encargan de transferir datos entre las capas de la aplicación, garantizando que los datos sean consistentes y desacoplando las dependencias entre componentes.

- **Http**:  
  Maneja todo lo relacionado con las solicitudes HTTP y la interacción con el cliente.
  - **Controllers**: Procesan las solicitudes entrantes, interactúan con los servicios y devuelven respuestas.
  - **Requests**: Validan los datos enviados en las solicitudes HTTP antes de que lleguen a los controladores.
  - **Middleware**: Actúan como filtros para procesar solicitudes y respuestas, como la autenticación o la validación de permisos.

- **Services**:  
  Contienen la lógica del negocio, como el cálculo de salarios, la validación de horas trabajadas y otros procesos centrales de la aplicación.  

- **Traits**:  
  Contienen métodos reutilizables entre diferentes clases, promoviendo la reutilización del código y la cohesión.

- **Eventos y Jobs**:  
  - **Eventos**: Representan acciones específicas en la aplicación, como la creación de una nueva sesión laboral o el cálculo de un salario.  
  - **Jobs**: Manejan tareas asíncronas, como el procesamiento del cálculo de salarios o el envío de notificaciones.  
  - **Listeners**: Responden a los eventos y desencadenan los Jobs u otras acciones necesarias.

- **Models**:  
  Representan las entidades del dominio y actúan como puente entre la base de datos y la aplicación. Contienen relaciones y métodos de consulta relacionados con las entidades.

### Beneficios de esta Arquitectura

1. **Modularidad**: Cada capa tiene responsabilidades específicas, lo que facilita la mantenibilidad y escalabilidad.
2. **Reutilización**: Uso de Traits y DTOs para evitar redundancias y promover un código más limpio.
3. **Asincronía**: Con Eventos, Jobs y Listeners, el sistema puede manejar procesos en segundo plano, optimizando la experiencia del usuario y el rendimiento de la aplicación.
4. **Desacoplamiento**: Las capas están diseñadas para ser independientes entre sí, lo que facilita la implementación de cambios sin afectar todo el sistema.

## Instalación y Configuración

Para instalar y configurar la API de Jornalia, sigue estos pasos:

### Clonación del Repositorio

1.Clona este repositorio en tu máquina local:

```bash
git clone <url-del-repositorio>
```
2.Instalación de Dependencias:

Navega al directorio del proyecto y ejecuta el siguiente comando para instalar las dependencias de Laravel:

```bash
cd jornAPI
composer install
```
3.Configuración del Entorno:

Copia el archivo .env.example a .env y actualiza las variables según tu configuración:

```bash

cp .env.example .env
```
4.Configura las variables de base de datos, correo y Redis según tus necesidades.
Genera la clave de la aplicación:
```bash
php artisan key:generate
```
5.Migración de la Base de Datos:

Ejecuta las migraciones y seeders para crear las tablas necesarias en la base de datos:

```bash
php artisan migrate --seed
```
Configuración de Roles con Spatie Permissions
Si estás utilizando Spatie Permissions para gestionar roles y permisos, sigue estos pasos adicionales:

Uso del trait HasUuid en la clase Role:
Spatie Permissions por defecto utiliza identificadores incrementales para los roles y permisos. Si deseas usar UUIDs en lugar de IDs incrementales, debes agregar el trait HasUuid en la clase Role que se encuentra en el directorio del vendor spatie/laravel-permission.

Abre el archivo Role.php en vendor/spatie/laravel-permission/src/Models/Role.php y agrega el siguiente trait:

```php
use \Illuminate\Support\Str;

class Role extends Role
{
    use HasUuid;

}
```
Esto cambiará la forma en que se gestionan los identificadores de los roles, permitiendo utilizar UUIDs en lugar de los identificadores automáticos de la base de datos.

### Instalación y Configuración de Redis
Para interactuar con Redis, es necesario tener instalado redis-cli. Si no lo tienes instalado, puedes seguir las instrucciones de instalación desde aquí.

Una vez instalado Redis, asegúrate de configurar las variables de Redis en el archivo .env según tu entorno.
# Endpoints

## Autenticación
### Registro de empleado
**POST** `/register`  
- **Descripción:** Registra un nuevo empleado.  
- **Autenticación:** No requerida.
- **Cuerpo de la solicitud:**
  - `name` (**String, Required**) - Nombre del empleado.  
  - `email` (**String, Required**) - Correo electrónico único del empleado.  
  - `password` (**String, Required**) - Contraseña para autenticación del empleado.  
  - `normal_hourly_rate` (**Decimal, Required**) - Tarifa por hora normal del empleado.  
  - `overtime_hourly_rate` (**Decimal, Required**) - Tarifa por hora extra del empleado.  
  - `holiday_hourly_rate` (**Decimal, Required**) - Tarifa por hora en días festivos.  
  - `irpf` (**Decimal, Optional**) - Porcentaje de retención del IRPF, opcional.

### Inicio de sesión
**POST** `/login`  
- **Descripción:** Inicia sesión para obtener un token JWT.  
- **Autenticación:** No requerida.
- **Cuerpo de la solicitud:**
  - `email` (**String, Required**) - Correo electrónico registrado del usuario.  
  - `password` (**String, Required**) - Contraseña del usuario. 

### Cierre de sesión
**POST** `/logout`  
- **Descripción:** Cierra la sesión del usuario actual.  
- **Autenticación:** Requerida (JWT).  

---

## Rutas de Usuario
### Actualizar Usuario
**PUT** `/user/update`  
- **Descripción:** Actualiza el email del usuario.  
- **Autenticación:** Requerida (JWT).
- **Cuerpo de la solicitud:**
  - `email`  

### Mostrar Usuario
**GET** `/user/show`  
- **Descripción:** Muestra el email del usuario.  
- **Autenticación:** Requerida (JWT).  

### Eliminar Usuario
**POST** `/user/delete`  
- **Descripción:** Elimina al usuario.  
- **Autenticación:** Requerida (JWT).  

---

## Rutas de Empleado
### Mostrar Empleado
**GET** `/employee`  
- **Descripción:** Muestra información del empleado (nombre, nombre de la empresa, tarifa por hora normal, horas extra y horas festivas).  
- **Autenticación:** Requerida (JWT).  

### Actualizar Empleado
**PUT** `/employee`  
- **Descripción:** Actualiza los datos del empleado, puedes enviar uno o varios campos para actualizar.  
- **Autenticación:** Requerida (JWT).
- 

---

## Rutas de Sesión de Horas
### Crear Sesión de Horas
**POST** `/hour_session`  
- **Descripción:** Crea una nueva sesión de horas.  
- **Autenticación:** Requerida (JWT).  
- **Datos requeridos:**  
  - `date` (**String, Required**) - Fecha de la sesión en formato `yyyy-mm-dd`.
  - `start_time` (**String, Required**) - Hora de inicio en formato `HH:mm`.
  - `end_time` (**String, Required**) - Hora de fin en formato `HH:mm`.
  - `planned_hours` (**Integer, Required**) - Número de horas previstas para la sesión.
  - `work_type` (**String, Optional**) - Tipo de trabajo, valores posibles:  
    - `is_normal` (por defecto si no se especifica)  
    - `is_holiday`  
    - `is_overtime` 

### Mostrar Sesión de Horas
**GET** `/hour_session`  
- **Descripción:** Muestra la sesión de horas de una fecha específica.  
- **Autenticación:** Requerida (JWT).  
- **Parámetros de consulta:**  
  - `date` (formato: `yyyy-mm-dd`)

### Actualizar Sesión de Horas
**PUT** `/hour_session`  
- **Descripción:** Actualiza una sesión de horas basada en la fecha.  
- **Autenticación:** Requerida (JWT).  
- **Parámetros de consulta:**  
  - `date` (formato: `yyyy-mm-dd`)

### Eliminar Sesión de Horas
**DELETE** `/hour_session`  
- **Descripción:** Elimina una sesión de horas específica.  
- **Autenticación:** Requerida (JWT).  
- **Parámetros de consulta:**  
  - `date` (formato: `yyyy-mm-dd`)

---

## Dashboard
### Mostrar Dashboard
**GET** `/dashboard`  
- **Descripción:** Muestra datos del mes actual, como la totalidad de horas trabajadas y el sueldo ganado.  
- **Autenticación:** Requerida (JWT).  

---

## Rutas de Salarios
### Mostrar Salario por Mes
**GET** `/salary`  
- **Descripción:** Muestra el salario de un mes específico.  
- **Autenticación:** Requerida (JWT).  
- **Parámetros de consulta:**  
  - `month`  
  - `year`

