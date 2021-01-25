<?php

namespace Cratespace\Preflight\Tests;

use Cratespace\Preflight\API\Permission;

class PermissionTest extends TestCase
{
    public function testRolesCanBeRegistered()
    {
        Permission::$permissions = [];
        Permission::$roles = [];

        Permission::role('admin', 'Admin', [
            'read',
            'create',
        ])->description('Admin Description');

        Permission::role('editor', 'Editor', [
            'read',
            'update',
            'delete',
        ])->description('Editor Description');

        $this->assertTrue(Permission::hasPermissions());

        $this->assertEquals([
            'create',
            'delete',
            'read',
            'update',
        ], Permission::$permissions);
    }
}
