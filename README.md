# Prueba Técnica de Desarrollo Web

Este proyecto es una aplicación web desarrollada como parte de una prueba técnica para evaluar habilidades en desarrollo full-stack con **Laravel 10**, **Vue.js 3**, **Inertia.js** y **SQLite**. La aplicación permite gestionar el registro y visualización de piezas relacionadas con proyectos y bloques.

---

## 🎯 Objetivo

Visualizar un listado con toda la información de las piezas registradas a "La Minucia", incluyendo su registro, validación, filtrado por estado y reportes.

---

## ⚙️ Tecnologías utilizadas

- **Back-end**: Laravel 10 + Eloquent ORM
- **Front-end**: Vue.js 3 + Inertia.js
- **Base de datos**: SQLite
- **Autenticación**: Laravel Breeze / Jetstream (dependiendo de implementación)
- **Estilo**: Bootstrap + diseño responsive (RA)

---

## 🧪 Funcionalidades implementadas

### 🔐 Autenticación

- ✅ Login básico de usuarios autorizados (RB)
- ✅ Redirección al formulario si login es exitoso (RB)
- ✅ Prevención de acceso directo a rutas protegidas (RA)

### 📋 Formulario de registro de piezas

- ✅ Fecha y hora automática del sistema (RB)
- ✅ Selección de proyecto (RB)
- ✅ Selección anidada de bloque según proyecto (RB)
- ✅ Selección de pieza filtrada por bloque y estado “Pendiente” (RA)
- ✅ Visualización automática del peso teórico (RB)
- ✅ Ingreso de peso real y validación numérica (RB)
- ✅ Cálculo automático de diferencia entre peso teórico y real (RA)
- ✅ Validaciones en el cliente (RA)
- ✅ Persistencia de datos con validación del lado del servidor (RB + RA)
- ✅ Responsive design para móviles y escritorio (RA)

### 📊 Reportes

- 📌 Listado de piezas pendientes agrupadas por proyecto (RA)
- 📌 Reporte gráfico de piezas pendientes y fabricadas por proyecto (RA)

---

## 🗃️ Estructura de base de datos

Se utilizan al menos las siguientes tablas con relaciones:

1. `usuarios`
2. `proyectos`
3. `bloques`
4. `piezas`
5. `registros` (almacena los datos ingresados desde el formulario)

Cada tabla cuenta con su respectivo CRUD utilizando Eloquent y migraciones de Laravel. Se pueden haber añadido campos o tablas auxiliares justificadamente.

---

## 🚀 Instalación y uso

```bash
git clone https://github.com/andrw3110/SistemaDePiezas
cd tu_repositorio
cp .env.example .env
php artisan key:generate
# Configurar .env para usar sqlite:
touch database/database.sqlite
# Asegúrate de tener DB_CONNECTION=sqlite en el .env
php artisan migrate --seed
npm install && npm run build
php artisan serve

