<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Usuarios
            'ver_usuarios', 'crear_usuarios', 'editar_usuarios', 'eliminar_usuarios',
            // Funciones
            'ver_funciones', 'crear_funciones', 'editar_funciones', 'eliminar_funciones',
            // Reservas
            'ver_reservas', 'crear_reservas', 'editar_reservas', 'cancelar_reservas', 'check_in_reservas',
            // Mesas
            'ver_mesas', 'editar_estado_mesas',
            // Reportes
            'ver_reportes', 'exportar_reportes',
            // Configuración
            'ver_configuracion', 'editar_configuracion',
            // Logs
            'ver_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $encargado = Role::create(['name' => 'encargado']);
        $cliente = Role::create(['name' => 'cliente']);

        // Asignar permisos a Admin (todos)
        $admin->givePermissionTo(Permission::all());

        // Asignar permisos a Encargado
        $encargado->givePermissionTo([
            'ver_funciones', 'crear_funciones', 'editar_funciones', 'eliminar_funciones',
            'ver_reservas', 'editar_reservas', 'cancelar_reservas', 'check_in_reservas',
            'ver_mesas', 'editar_estado_mesas',
            'ver_reportes',
        ]);

        // Asignar permisos a Cliente
        $cliente->givePermissionTo([
            'ver_funciones',
            'crear_reservas',
            'cancelar_reservas',
        ]);
    }
}
