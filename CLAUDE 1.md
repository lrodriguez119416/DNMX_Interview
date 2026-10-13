# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

---

## Commands

### Frontend (Quasar / Vue 3)

```bash
# Dev server
npx quasar dev

# Build producción
npx quasar build

# Lint
npx eslint src/ --ext .ts,.vue
```

### Backend (ASP.NET Core)

```bash
# Build
dotnet build

# Run (el puerto depende del proyecto, ver launchSettings.json)
dotnet run --project <Proyecto>/<Proyecto>.csproj

# EF Core migrations
dotnet ef migrations add <NombreMigración> --project <Proyecto>
dotnet ef database update --project <Proyecto>
```

---

## Frontend — Estándares de Desarrollo

### Stack obligatorio

- **Vue 3** + **Composition API** (`<script setup lang="ts">`)
- **Quasar Framework v2** como UI framework
- **TypeScript** estricto — **nunca usar `any`**
- **Pinia** para state management (Composition API style con `defineStore` + `ref`)
- **Axios** para HTTP, instancias centralizadas en `src/api/`
- **Vue Router 4** con history mode

### Estructura de proyecto

```
src/
├── api/              # Instancias Axios (apiSY.ts, apiQA.ts, apiKanban.ts, etc.)
├── boot/             # Plugins Quasar (i18n, gantt, etc.)
├── css/              # SCSS global (app.scss, quasar.variables.scss)
├── router/
│   ├── index.ts      # Router + guards de autenticación
│   ├── routes.ts     # Rutas principales
│   └── interfaces/   # RouterLink interface
├── stores/           # Pinia stores (sessionStorage.ts / loginStore.ts)
├── views/
│   ├── <modulo>/     # Carpeta por módulo funcional
│   │   ├── <modulo>Page.vue
│   │   └── components/
│   ├── shared/
│   │   ├── components/   # Componentes reutilizables (sideBar, topBar, dialogs)
│   │   ├── interfaces/   # Todas las interfaces TypeScript
│   │   ├── helpers/      # Funciones utilitarias (http.ts, validaciones)
│   │   ├── constants/    # Constantes globales
│   │   └── pages/        # Páginas de error (403, 404)
│   └── router/
│       └── routes.ts     # Rutas hijas del layout principal
└── App.vue
```

### TypeScript — Interfaces en archivo separado

Todas las interfaces se definen en archivos dedicados dentro de `views/shared/interfaces/`, nunca inline en componentes. Ejemplo:

```typescript
// views/shared/interfaces/SYInterfaces.ts
export interface iClient {
    clientId: number;
    name: string;
    municipality: string;
    isActive: boolean;
    createdAt: date;
}
```

Importar desde el componente:
```typescript
import type { iClient } from 'src/views/shared/interfaces/SYInterfaces';
```

### Conexión API — Archivo `api.ts` centralizado

Cada proyecto tiene su archivo de instancias Axios en `src/api/`. Patrón estándar:

```typescript
import axios from 'axios';

const path = process.env.RELEASE_PROD
    ? window.location.origin
    : process.env.API_ORIGIN;

const apiSY = axios.create({
    baseURL: `${path}:${process.env.API_PORT}`,
    validateStatus: (status: number) => status >= 200 && status < 500,
});

export { apiSY };
```

Las URLs base se configuran en las variables de entorno de `quasar.config.js`, **nunca hardcodeadas en componentes**.

### Manejo de errores HTTP

Usar siempre `try/catch` en llamadas API. Utilizar el helper `src/views/shared/helpers/http.ts` para extraer mensajes de error con mapeo de códigos HTTP. Notificar al usuario con `$q.notify()`:

```typescript
try {
    const { status, data } = await apiSY.get('/endpoint');
    if (status === 200) {
        // procesar data
    } else {
        $q.notify({ type: 'negative', message: data?.message || 'Error' });
    }
} catch (error) {
    $q.notify({ type: 'negative', message: getApiErrorMessage(error) });
}
```

Códigos de error mapeados en español (400=Solicitud inválida, 401=No autorizado, 403=Sin permisos, 404=No encontrado, 409=Conflicto, 500=Error del servidor).

### Diseño UX/UI

**Estilo visual unificado** basado en ScrapYard e IoTHUB:
- **Glassmorphism**: Fondos semi-transparentes con `backdrop-filter: blur()`, bordes `rgba(255,255,255,0.5)`
- **Paleta**: Verdes (#2d6a4f, #52b788), Morados (#667eea, #764ba2), Azules (#2563eb), Slate para texto
- **Tipografía**: Inter font family, pesos 300–700
- **Iconos**: Material Icons + FontAwesome v5
- **Login**: Fondo animado con orbes gradient flotantes
- **Layout**: TopBar glass + SideBar con links activos en gradient + contenido en `<q-page-container>`

**Reglas de layout obligatorias**:
- Validar siempre tamaños máximos de texto con `text-overflow: ellipsis` o truncado Quasar (`lines` prop en `q-item-label`)
- Preparar componentes para nombres largos y casos especiales (ej. nombres de 60+ caracteres)
- Evitar desbordes: usar `overflow: hidden` o `auto` en contenedores, probar con datos extremos
- Tablas: usar `q-table` con columnas de ancho definido, `style="max-width"` en celdas de texto
- Diálogos: usar `persistent` en formularios para evitar cierre accidental
- Formularios: validación en tiempo real con `rules` de Quasar

**Componentes modulares y escalables**:
- Un archivo `.vue` por página, componentes hijos en subcarpeta `components/`
- Diálogos de CRUD como componentes separados (`addXComponent.vue`, `updateXComponent.vue`)
- Los props y emits deben estar tipados

### Routing y autenticación

Guard de autenticación en `router/index.ts` con `beforeEach`:
- Si no autenticado → redirigir a login
- Control de permisos por rol via `meta.users: number[]` en las rutas
- Si usuario no tiene permiso → redirigir a `forbiddenPage`

### Pinia Store — Sesión

Patrón estándar para store de sesión con persistencia en LocalStorage:

```typescript
export const useSessionStore = defineStore('session', () => {
    const userData = ref<userInfo>({ isAutenticate: false });

    const createSession = (data: userInfo) => { userData.value = data; };

    if (LocalStorage.getItem('userKey')) {
        userData.value = JSON.parse(LocalStorage.getItem('userKey') || '{}');
    }

    watch(userData, (val) => {
        LocalStorage.set('userKey', JSON.stringify(val));
    }, { deep: true });

    const removeSession = () => {
        userData.value.isAutenticate = false;
        LocalStorage.remove('userKey');
    };

    return { userData, createSession, removeSession };
});
```

---

## Backend — Estándares de Desarrollo

### Stack obligatorio

- **ASP.NET Core Web API** sobre **.NET 8.0**
- **Entity Framework Core 9.x** con SQL Server
- **Swagger/OpenAPI** en `/swagger` (siempre habilitado)

### Solo GET y POST

Todos los controladores usan exclusivamente `[HttpGet]` y `[HttpPost]`. No usar PUT, PATCH ni DELETE. Las actualizaciones y eliminaciones se hacen via POST:

```csharp
[HttpGet("GetAll")]
[HttpGet("GetById/{id}")]
[HttpPost("Create")]
[HttpPost("Update/{id}")]
[HttpPost("Delete/{id}")]    // Borrado lógico, nunca físico
```

### Borrado lógico obligatorio

Nunca eliminar registros de la base de datos. Usar campos de estado:

```csharp
// Opción 1: bool isDeleted (preferida para nuevos proyectos)
public bool isDeleted { get; set; } = false;

// Opción 2: bool IsActive
public bool IsActive { get; set; } = true;

// Opción 3: int Status (1=activo, 0=eliminado)
public int Status { get; set; } = 1;
```

Siempre filtrar en las consultas:
```csharp
.Where(x => !x.isDeleted)
.Where(x => x.IsActive)
.Where(x => x.Status == 1)
```

### DateTime — Siempre UTC

Usar `DateTime.UtcNow` para toda marca de tiempo del servidor:

```csharp
entity.CreatedAt = DateTime.UtcNow;
entity.UpdatedAt = DateTime.UtcNow;
```

### Request models en archivos externos

Los parámetros de las peticiones se definen en clases Request separadas, no en el controlador:

```csharp
// Requests/CreateMaterialRequest.cs
public class CreateMaterialRequest
{
    public string Name { get; set; } = string.Empty;
    public int CategoryId { get; set; }
}
```

### Configuración en appsettings.json

**Rutas de API externas (dispatchers)** — Definir en appsettings para poder apuntar a distintos servidores sin recompilar:

```json
{
  "ConnectionStrings": {
    "MainDB": "Server=...; Database=...; encrypt=false; ..."
  },
  "Paths": {
    "File_base": "C:\\files\\proyecto\\"
  },
  "ApiBasics": "http://localhost:8198/",
  "GeneralConfig": {
    "ExternalApiUrl": "http://localhost:8084/"
  }
}
```

Inyectar en `Program.cs`:
```csharp
builder.Services.AddSingleton<ConstantsAPI>(
    builder.Configuration.GetSection("Paths").Get<ConstantsAPI>());
```

### Almacenamiento de archivos — Siempre fuera del repo

Imágenes y archivos se guardan en rutas del sistema de archivos definidas en `appsettings.json` bajo la sección `Paths`, **nunca dentro del repositorio**. Servir como archivos estáticos:

```csharp
var filesPath = builder.Configuration["Paths:File_base"] ?? "C:/files/default";
if (!Directory.Exists(filesPath)) Directory.CreateDirectory(filesPath);

app.UseStaticFiles(new StaticFileOptions
{
    FileProvider = new PhysicalFileProvider(filesPath),
    RequestPath = "/files"
});
```

### Patrón de Program.cs

```csharp
var builder = WebApplication.CreateBuilder(args);
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();
builder.Services.AddDbContext<MyContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("MainDB")));
builder.Services.AddHttpClient();

var app = builder.Build();
app.UseSwagger();
app.UseSwaggerUI();
app.UseAuthorization();
app.UseCors(x => x.AllowAnyMethod().AllowAnyOrigin().AllowAnyHeader());
app.MapControllers();
app.Run();
```

### Build configurations (compilación condicional)

Los proyectos usan directivas del compilador para seleccionar entorno:

```csharp
#if RELEASE
var environment = "RELEASE";
#elif DEBUG
var environment = "Debug";
#elif APP_PLANT
var environment = "APP_PLANT";
#endif

builder.Configuration.AddJsonFile($"appsettings_{environment}.json");
```

Cada entorno tiene su propio `appsettings_{env}.json` con connection strings y paths específicos.

### Manejo de errores en controladores

```csharp
try {
    // lógica
    return StatusCode(StatusCodes.Status200OK, new { message = "Éxito", data = result });
} catch (Exception ex) {
    return StatusCode(StatusCodes.Status500InternalServerError, new { message = ex.Message });
}
```

Códigos estándar: 200 éxito, 400 datos inválidos, 404 no encontrado, 409 conflicto/duplicado, 500 error del servidor.

### Código escalable y entendible

- Nombres descriptivos en inglés para código, español solo en mensajes al usuario
- Un controlador por entidad o módulo funcional
- Queries con `.Include()` y `.ThenInclude()` para relaciones, evitar N+1
- JSON serialization con `ReferenceHandler.IgnoreCycles` cuando hay relaciones circulares
- Named HttpClients para APIs externas con `builder.Services.AddHttpClient("name", ...)`
