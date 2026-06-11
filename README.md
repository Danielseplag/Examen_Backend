# TodoCamisetas API - Examen Transversal Final

API RESTful desarrollada en PHP Puro (sin frameworks) para el sistema de gestión B2B de TodoCamisetas. Este proyecto permite administrar el inventario de camisetas, la base de clientes y calcular dinámicamente ofertas especiales según la categoría del cliente.

---

## 🛠️ Stack Tecnológico

*   **Lenguaje:** PHP 8.2 (Puro / Nativo)
*   **Base de Datos:** MySQL 8
*   **Servidor Web:** Nginx
*   **Orquestación:** Docker & Docker Compose
*   **Arquitectura:** MVC (Model-View-Controller)
*   **Pruebas:** Postman Collection incluida

---

## 🚀 Instalación y Ejecución Rápida

El proyecto está completamente contenerizado con Docker, lo que garantiza que funcione en cualquier entorno sin requerir instalación local de PHP o MySQL.

### 1. Levantar los contenedores
Abre tu terminal en la raíz del proyecto y ejecuta:
```bash
docker compose up -d
```
*Esto descargará las imágenes necesarias (si no las tienes) y levantará los servicios de Nginx, PHP y MySQL.*

### 2. Ejecutar las Migraciones (Base de Datos)
Una vez que los contenedores estén corriendo, instala las dependencias y crea las tablas de la base de datos:
```bash
docker compose exec php composer install
docker compose exec php php migrate.php
```

¡Listo! La API estará corriendo y escuchando en `http://localhost:8080`.

---

## 🧪 Cómo Probar la API

Para facilitar la corrección y evaluación del examen, se incluye una colección completa de Postman.

1. Abre **Postman**.
2. Haz clic en el botón **Import**.
3. Selecciona el archivo `TodoCamisetas_Postman_Collection.json` que se encuentra en la raíz de este proyecto.
4. Explora las carpetas "Camisetas" y "Clientes" para probar los métodos CRUD.
5. Usa el endpoint **"Obtener Precio Final"** para validar la regla de negocio que calcula dinámicamente el precio final dependiendo de si el cliente es "Preferencial" o "Regular".

---

## 🌊 Flujo del Proyecto (Arquitectura y Peticiones)

El ciclo de vida de una petición dentro de nuestra API sigue el patrón MVC estricto:

1. **Cliente (Postman/Front):** Realiza una solicitud HTTP (ej. `GET /api/camisetas/1/precio-final?cliente_id=1`).
2. **Nginx:** Recibe la petición en el puerto 8080 y la redirige al contenedor de PHP (`index.php`).
3. **Enrutador (`router.php`):** Analiza la URL usando expresiones regulares y delega la ejecución al Controlador correspondiente (ej. `CamisetaController::getPrecioFinal`).
4. **Controlador:** Recibe la petición, valida los datos de entrada y solicita la información requerida a los Modelos.
5. **Modelo (`Camiseta.php`, `Cliente.php`):** Se conecta a MySQL mediante PDO, ejecuta la consulta segura y devuelve la información de la base de datos al Controlador.
6. **Lógica de Negocios:** El Controlador procesa los datos de los modelos (ej. aplicar el descuento si es un cliente *Preferencial*).
7. **Respuesta (`Response.php`):** El Controlador emite un JSON estructurado con el resultado exitoso (HTTP 200) o un error si fallaron las validaciones.

---

## 📂 Estructura Principal del Código

```text
📁 Examen_Backend/
 ├── 📄 docker-compose.yml       # Orquestación de Nginx, PHP y MySQL
 ├── 📄 TodoCamisetas_Postman_Collection.json  # Peticiones de prueba
 ├── 📁 docker/                  # Configuraciones (nginx.conf, Dockerfile)
 └── 📁 src/
      ├── 📄 composer.json       # Dependencias y Autoloader PSR-4
      ├── 📄 migrate.php         # Script de consola para ejecutar BD
      ├── 📁 config/
      │    ├── Database.php      # Conexión a MySQL (Singleton PDO)
      │    └── Response.php      # Formateador de JSON y validaciones
      ├── 📁 controllers/
      │    ├── CamisetaController.php # Lógica HTTP de camisetas
      │    ├── ClienteController.php  # Lógica HTTP de clientes
      │    ├── OrdenController.php    # Lógica HTTP de órdenes
      │    └── TallaController.php    # Lógica HTTP de tallas
      ├── 📁 database/
      │    ├── Migration.php     # Ejecutor de scripts SQL
      │    └── 📁 migrations/    # Scripts SQL (001 a 006)
      ├── 📁 models/
      │    ├── Camiseta.php      # Reglas de negocio e INSERTs de Camisetas
      │    ├── Cliente.php       # Reglas de negocio e INSERTs de Clientes
      │    ├── Orden.php         # Interacción con tabla órdenes
      │    └── Talla.php         # Interacción con tabla tallas
      ├── 📁 public/
      │    └── index.php         # Punto de entrada (Front Controller)
      └── 📁 routes/
           └── router.php        # Enrutador Regex y endpoint Precio Final
```

---

## 👥 Equipo de Trabajo

**Desarrollo Backend - IF201IINF**

*   Barrera
*   Ramírez
*   Sepúlveda
*   Torres

*Instituto Profesional San Sebastián - Junio 2026*
