# 🏡 Sistema de Gestión de Viviendas - Actividad 20

## 📄 Descripción del Proyecto

Este proyecto fue desarrollado para la **Actividad 20: Implementación de Listados y Filtrados Dinámicos** de la materia **Proyecto de Implementación de Sitios web Dinámicos (PWD)**.

El objetivo principal es demostrar la capacidad de listar, paginar y filtrar registros de una base de datos MySQL (`inmobiliaria`) utilizando PHP, implementando diferentes niveles de complejidad, incluyendo un sistema de búsqueda avanzada con filtros múltiples y una versión con tecnología **AJAX**.

---

## ✨ Funcionalidades

El proyecto se estructura en varios ejercicios que demuestran diferentes técnicas de manejo de datos dinámicos:

| Archivo | Funcionalidad | Descripción |
| :--- | :--- | :--- |
| `ejercicio1.php` | **Listado Simple** | Muestra **todas** las viviendas de la base de datos sin aplicar paginación ni filtros. |
| `ejercicio2.php` | **Listado Paginado** | Muestra las viviendas con un límite de registros por página y navegación con botones "Anterior" y "Siguiente". |
| `filtros.php` | **Búsqueda Avanzada** | Sistema completo de **filtros dinámicos** por tipo, zona, rango de precio, dormitorios y extras, combinado con paginación. |
| `ajax.php` | **Versión AJAX** | Implementación de la funcionalidad de filtros y paginación utilizando **JavaScript Fetch API** para una carga de resultados instantánea sin recargar la página. |
| `index.php` | **Menú Principal** | Página de inicio que enlaza a cada una de las funcionalidades desarrolladas. |

---

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP
* **Base de Datos:** MySQL (con archivos SQL para la estructura y datos de prueba)
* **Frontend:** HTML5, CSS3, JavaScript (incluyendo AJAX/Fetch API)

## ⚙️ Configuración e Instalación

Para ejecutar este proyecto de forma local, necesitas un entorno de servidor web que soporte PHP y MySQL (como **XAMPP**, **WAMP** o **MAMP**).

### 1. Base de Datos

1.  Abre tu herramienta de administración de bases de datos (por ejemplo, phpMyAdmin).
2.  Importa el archivo `inmobiliaria.sql`. Este archivo creará la base de datos `inmobiliaria` y la tabla `viviendas` con datos de ejemplo necesarios.

### 2. Configuración de Conexión

1.  Abre el archivo `config.php`.
2.  Verifica y ajusta las constantes de conexión si tu configuración local no es la predeterminada:

    ```php
    // Configuración por defecto (XAMPP/WAMP):
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'inmobiliaria');
    ```

### 3. Ejecución

1.  Coloca todos los archivos del proyecto en el directorio raíz de tu servidor web (ej. `htdocs` en XAMPP).
2.  Accede a la aplicación a través de tu navegador, generalmente en: `http://localhost/[nombre-de-la-carpeta]/index.php`

---

## 🏗️ Estructura de la Base de Datos

El proyecto utiliza una única tabla llamada `viviendas` dentro de la base de datos `inmobiliaria`.

| Campo | Tipo de Dato | Descripción |
| :--- | :--- | :--- |
| `id` | `INT(3)` | Identificador único (Clave Primaria, Auto Incremental). |
| `tipo` | `VARCHAR(15)` | Tipo de vivienda (Casa, Departamento, etc.). |
| `zona` | `VARCHAR(15)` | Zona o barrio. |
| `direccion` | `VARCHAR(30)` | Dirección de la propiedad. |
| `dormitorios` | `INT(1)` | Cantidad de dormitorios. |
| `precio` | `FLOAT(10,2)` | Precio de venta. |
| `tamano` | `INT(5)` | Tamaño en metros cuadrados (m²). |
| `extras` | `SET` | Conjunto de extras disponibles (`Piscina`, `Jardín`, `Garage`). |

---

## 📚 Contexto Académico

* **Materia:** Proyecto de Implementación de Sitios web Dinámicos
* **Institución:** EEST N.º 1 "Eduardo Ader" - Vicente López
* **Curso/Año:** 7° 2° B - 2025

---

## ⚖️ Licencia

Este proyecto está liberado bajo la **Licencia MIT**. Puedes encontrar los detalles completos en el archivo [LICENSE](LICENSE).
