# 🏛️ Proyecto Web Hermandad - Roadmap Tecnológico

## 🛠 Stack Tecnológico
- **Core:** Laravel 11 (Backend & Routing)
- **Frontend:** React + Inertia.js (Sin APIs manuales, renderizado rápido)
- **Estilos:** Tailwind CSS (Responsive nativo)
- **Admin Panel:** Filament PHP (Gestión de noticias, fotos y hermanos)
- **Animaciones:** Framer Motion (Para el toque elegante y solemne)
- **Base de Datos:** MySQL / PostgreSQL

---

## 🏗️ Esquema de Arquitectura
El proyecto se estructura como un único repositorio gestionado por **Inertia.js**:

1. **Backend (Laravel):**
   - Controladores que devuelven `Inertia::render('NombrePagina')`.
   - Modelos para: `Noticia`, `Evento`, `Hermano`, `Titular`, `Patrimonio`.
   - SEO: Laravel maneja las etiquetas meta dinámicamente.

2. **Frontend (React):**
   - Ubicado en `/resources/js/Pages`.
   - Componentes reutilizables en `/resources/js/Components`.

3. **Panel Administrativo (Filament):**
   - Acceso en `/admin`.
   - Gestión visual de la base de datos sin programar CRUDs a mano.

---

## 🚀 Comandos para Comenzar (Paso a Paso)

Ejecuta estos comandos en tu terminal una vez tengas el entorno PHP/Composer listo:

### 1. Crear el proyecto Laravel con el Starter Kit (Breeze + React + SSR)
```bash
# Crear proyecto
composer create-project laravel/laravel web-hermandad

cd web-hermandad

# Instalar Laravel Breeze para tener React + Inertia configurado
composer require laravel/breeze --dev

# Configurar Breeze con React y soporte para SEO (SSR)
php artisan breeze:install react --ssr