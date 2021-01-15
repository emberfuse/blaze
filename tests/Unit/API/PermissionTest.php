<?php

namespace Tests\Unit\API;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_set_permissions_for_application()
    {
        Permission::permissions([
            'create',
            'read',
        ]);

        $this->assertDatabaseHas('permissions', ['title' => 'create']);
        $this->assertDatabaseHas('permissions', ['title' => 'read']);
    }

    public function test_set_default_api_token_permissoin()
    {
        Permission::defaultApiTokenPermissions(['read']);

        $this->assertEquals(['read'], Permission::$defaultPermissions);
    }

    public function test_can_determine_if_permission_have_been_registered()
    {
        Permission::permissions([
            'create',
            'read',
        ]);

        $this->assertTrue(Permission::hasPermissions());
    }

    public function test_filter_valid_permissions()
    {
        Permission::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);

        $this->assertEquals(
            [
                'update',
                'delete',
            ],
            Permission::validPermissions([
                'update',
                'delete',
            ])
        );
    }
}
