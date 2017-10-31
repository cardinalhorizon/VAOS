<?php

use Illuminate\Database\Seeder;

class InstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');

        $config         = config('laratrust_seeder.role_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission  = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {
            // Create a new role
            $role = \App\Models\Role::create([
          'name'         => $key,
          'display_name' => ucwords(str_replace('_', ' ', $key)),
          'description'  => ucwords(str_replace('_', ' ', $key)),
        ]);

            $this->command->info('Creating Role '.strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $permission = \App\Models\Permission::firstOrCreate([
              'name'         => $permissionValue.'-'.$module,
              'display_name' => ucfirst($permissionValue).' '.ucfirst($module),
              'description'  => ucfirst($permissionValue).' '.ucfirst($module),
            ]);

                    $this->command->info('Creating Permission to '.$permissionValue.' for '.$module);

                    if (! $role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    } else {
                        $this->command->info($key.': '.$p.' '.$permissionValue.' already exist');
                    }
                }
            }
        }
    }
}
