# 🗄️ CONFIGURAR MYSQL PARA MERAKI

## Opción 1: Iniciar MySQL desde XAMPP Control Panel (RECOMENDADO)

1. Abre **XAMPP Control Panel** (busca "XAMPP" en el menú inicio)
2. Click en el botón **"Start"** junto a **MySQL**
3. Espera a que el indicador se ponga verde
4. Continúa con el paso "Crear Base de Datos" más abajo

---

## Opción 2: Iniciar MySQL como Administrador (CMD)

1. Abre **CMD como Administrador** (click derecho → Ejecutar como administrador)
2. Ejecuta:
```cmd
net start MySQL80
```

---

## Crear Base de Datos

Una vez que MySQL esté corriendo, ejecuta estos comandos en tu terminal:

```bash
# Opción A: Desde la carpeta del proyecto
cd meraki-reservas
mysql -u root -e "CREATE DATABASE IF NOT EXISTS meraki_reservas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

O si tienes contraseña en MySQL:
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS meraki_reservas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## Configurar Laravel para MySQL

El archivo `.env` ya está configurado. Solo verifica estos valores:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meraki_reservas
DB_USERNAME=root
DB_PASSWORD=
```

Si tu MySQL tiene contraseña, agrégala en `DB_PASSWORD=tu_contraseña`

---

## Ejecutar Migraciones

Una vez creada la base de datos:

```bash
cd meraki-reservas
php artisan migrate:fresh --seed
```

Esto creará:
- ✅ Todas las tablas
- ✅ 25 mesas configuradas
- ✅ Roles y permisos
- ✅ 3 usuarios de prueba

---

## Verificar que funciona

```bash
php artisan tinker --execute="echo 'Mesas: ' . App\Models\Mesa::count() . PHP_EOL;"
php artisan tinker --execute="echo 'Usuarios: ' . App\Models\User::count() . PHP_EOL;"
```

Deberías ver:
```
Mesas: 25
Usuarios: 3
```

---

## Ver la Base de Datos

### Opción 1: phpMyAdmin (viene con XAMPP)
1. Asegúrate que MySQL esté corriendo en XAMPP
2. Abre: http://localhost/phpmyadmin
3. Usuario: `root`
4. Contraseña: (vacío por defecto)
5. Busca la base de datos `meraki_reservas`

### Opción 2: MySQL Workbench
1. Descarga: https://dev.mysql.com/downloads/workbench/
2. Conecta a:
   - Host: 127.0.0.1
   - Port: 3306
   - User: root
   - Password: (vacío)

### Opción 3: Línea de comandos
```bash
mysql -u root
USE meraki_reservas;
SHOW TABLES;
SELECT * FROM mesas;
SELECT * FROM users;
```

---

## Solución de Problemas

### Error: "Access denied for user 'root'"
Tu MySQL tiene contraseña. Agrégala en `.env`:
```env
DB_PASSWORD=tu_contraseña
```

### Error: "Unknown database 'meraki_reservas'"
La base de datos no existe. Créala:
```bash
mysql -u root -e "CREATE DATABASE meraki_reservas;"
```

### Error: "Can't connect to MySQL server"
MySQL no está corriendo. Inícialo desde XAMPP Control Panel.

### Puerto 3306 ocupado
Otro MySQL está corriendo. Detén otros servicios MySQL o cambia el puerto en `.env`:
```env
DB_PORT=3307
```

---

## Comandos Útiles

### Ver todas las tablas:
```bash
php artisan tinker --execute="DB::select('SHOW TABLES');"
```

### Ver usuarios:
```bash
php artisan tinker --execute="App\Models\User::all(['name', 'email']);"
```

### Ver mesas:
```bash
php artisan tinker --execute="App\Models\Mesa::all(['numero', 'zona', 'capacidad']);"
```

### Resetear base de datos:
```bash
php artisan migrate:fresh --seed
```

---

¡Listo! Una vez configurado MySQL, el sistema funcionará perfectamente. 🚀
