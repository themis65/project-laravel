# Catálogo Laravel API

Este proyecto es una API RESTful para la gestión de un catálogo de productos y categorías, desarrollada en **Laravel 11**. Incluye autenticación con Laravel Sanctum, roles de usuario (admin/cliente), y relaciones entre productos y categorías.

---

## Requisitos

- PHP >= 8.2
- Composer
- SQL Server (o el motor configurado en `.env`)
- Node.js y npm (opcional, solo si usas frontend con Laravel Mix)

---

## Instalación

1. **Clona el repositorio:**
   ```sh
   git clone https://github.com/tuusuario/catalogo-laravel.git
   cd catalogo-laravel
   ```

2. **Instala dependencias:**
   ```sh
   composer install
   ```

3. **Copia y configura el archivo `.env`:**
   ```sh
   cp .env.example .env
   ```
   - Configura la conexión a tu base de datos en el archivo `.env`.

4. **Genera la clave de la aplicación:**
   ```sh
   php artisan key:generate
   ```

5. **Ejecuta migraciones y seeders:**
   ```sh
   php artisan migrate:fresh --seed
   ```

6. **Inicia el servidor:**
   ```sh
   php artisan serve
   ```

---

## Usuarios de prueba

- **Admin:**  
  Email: `admin@tienda.com`  
  Password: `admin123`

- **Cliente:**  
  Email: `cliente@tienda.com`  
  Password: `cliente123`

---

## Endpoints principales

### Autenticación

- `POST /api/register` — Registrar usuario
- `POST /api/login` — Iniciar sesión (devuelve token)
- `POST /api/logout` — Cerrar sesión (requiere token)

### Productos

- `GET /api/productos` — Listar productos
- `GET /api/productos/{id}` — Ver producto
- `POST /api/productos` — Crear producto (**admin**)
- `PUT /api/productos/{id}` — Actualizar producto (**admin**)
- `DELETE /api/productos/{id}` — Eliminar producto (**admin**)

### Categorías

- `GET /api/categorias` — Listar categorías
- `POST /api/categorias` — Crear categoría (**admin**)
- `DELETE /api/categorias/{id}` — Eliminar categoría (**admin**)

---

## Ejemplo de uso de la API

### Login y uso de token

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tienda.com","password":"admin123"}'
```

Respuesta:
```json
{
  "user": { ... },
  "access_token": "TOKEN_AQUI",
  "token_type": "Bearer"
}
```

### Crear una categoría (requiere token de admin)

```bash
curl -X POST http://127.0.0.1:8000/api/categorias \
  -H "Authorization: Bearer TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Música"}'
```

### Crear un producto (requiere token de admin)

```bash
curl -X POST http://127.0.0.1:8000/api/productos \
  -H "Authorization: Bearer TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{"titulo":"Guitarra","descripcion":"Acústica","precio":150,"stock":5,"categorias":[1]}'
```

---

## Notas técnicas

- **Roles:** Solo usuarios con rol `admin` pueden crear, actualizar o eliminar productos y categorías.
- **Middleware:** El middleware `IsAdmin` protege las rutas de administración.
- **Relaciones:** Productos y categorías tienen relación muchos a muchos.
- **Seeders:** Se crean usuarios, categorías y productos de ejemplo al ejecutar los seeders.

---

## Estructura principal

- `app/Models/` — Modelos Eloquent
- `app/Http/Controllers/` — Controladores de la API
- `app/Http/Middleware/IsAdmin.php` — Middleware de rol admin
- `routes/api.php` — Rutas de la API
- `database/seeders/` — Seeders para datos de prueba

---

## Licencia

MIT

---

**¡Listo para usar y extender!**