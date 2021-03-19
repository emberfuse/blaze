<?php

namespace Cratespace\Preflight;

use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\Models\Role;
use Cratespace\Preflight\Tests\TestCase;
use Cratespace\Preflight\Models\Permission;
use Cratespace\Preflight\Tests\Fixtures\User;

class UserPermissionTest extends TestCase
{
    public function testUserCanBeAssignedRoles()
    {
        $this->migrate();

        $role = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => Hash::make('WhatsThatBehinfYourEar?'),
        ]);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('Administrator'));
    }

    public function testRolesCanBeAssignedPermissions()
    {
        $this->migrate();

        $role = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $permissions = ['purchase', 'create', 'view', 'delete'];

        foreach ($permissions as $permission) {
            Permission::create(['label' => $permission]);
        }

        $role->allowTo('purchase');

        $this->assertTrue($role->can('purchase'));
    }

    public function testUserCanBeAllowedPermissions()
    {
        $this->migrate();

        $role = Role::create(['name' => 'Administrator', 'slug' => 'admin']);
        $permissions = ['purchase', 'create', 'view', 'delete'];
        $user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => Hash::make('WhatsThatBehinfYourEar?'),
        ]);

        foreach ($permissions as $permission) {
            Permission::create(['label' => $permission]);
        }

        $role->allowTo('purchase');
        $user->assignRole($role);

        $this->assertTrue($user->findRole('Administrator')->can('purchase'));
    }
}
