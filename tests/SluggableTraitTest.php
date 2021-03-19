<?php

namespace Cratespace\Preflight;

use Illuminate\Support\Facades\Schema;
use Cratespace\Preflight\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Cratespace\Preflight\Tests\Fixtures\SluggableModel;

class SluggableTraitTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('sluggable_models', function (Blueprint $table) {
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('slug');
            $table->timestamps();
        });
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Schema::dropIfExists('sluggable_models');
    }

    public function testCanSetSlugAttributeOnCreate()
    {
        $model = SluggableModel::create([
            'name' => 'Totally overkill',
        ]);

        $this->assertEquals('totally-overkill', $model->slug);
    }

    public function testCanSetUniqueSlugAttributeOnCreate()
    {
        $model = SluggableModel::create([
            'name' => 'Totally overkill',
        ]);

        $modelDuplicate = SluggableModel::create([
            'name' => 'Totally overkill',
        ]);

        $this->assertNotEquals($modelDuplicate->slug, $model->slug);
    }

    public function testCanSetSlugAttributeOnUpdate()
    {
        $model = SluggableModel::create([
            'name' => 'Totally overkill',
        ]);

        $model->update(['name' => 'Totally overkilled']);

        $this->assertEquals('totally-overkilled', $model->slug);
    }

    public function testCanSetSameSlugIfSluggableFieldNotUpdated()
    {
        $model = SluggableModel::create([
            'name' => 'Totally overkill',
        ]);

        $model->update(['description' => 'Tottaly lost.']);

        $this->assertEquals('totally-overkill', $model->slug);
    }
}
