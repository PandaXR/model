<?php

use PHPUnit\Framework\TestCase;
use Lxr\Model\Model;

// Concrete subclass for testing purposes
class ConcreteModelStub extends Model
{
    protected $attributes = [
        'name' => 'Test Name',
        'age' => 30,
    ];

    public function getNameAttribute($value)
    {
        return strtoupper(
            (string) $value
        );
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}

class ModelTest extends TestCase
{
    public function testConstructorWithoutPassingAttributes()
    {
        $model = new ConcreteModelStub([]);

        $this->assertEquals(
            [
                'name' => 'Test Name',
                'age' => 30,
            ],
            $model->getAttributes()
        );
    }

    public function testConstructor()
    {
        $attributes = ['name' => 'New Name', 'age' => 25];
        
        $model = new ConcreteModelStub($attributes);
        
        $this->assertEquals(
            [
                'name' => strtolower($attributes['name']),
                'age' => $attributes['age']
            ],
            $model->getAttributes()
        );
    }

    public function testFill()
    {
        $model = new ConcreteModelStub();
        $attributes = ['name' => 'New Name', 'age' => 25];
        $model->fill($attributes);
        $this->assertEquals(
            [
                'name' => strtolower($attributes['name']),
                'age' => $attributes['age']
            ],
            $model->getAttributes()
        );
    }

    public function testForceFill()
    {
        $model = new ConcreteModelStub();
        $attributes = ['name' => 'New Name', 'age' => 25];
        $model->forceFill($attributes);
        $this->assertEquals(
            [
                'name' => strtolower($attributes['name']),
                'age' => $attributes['age']
            ],
            $model->getAttributes()
        );
    }

    public function testSetGetAttributes()
    {
        $model = new ConcreteModelStub();
        $model->setAttribute('name', 'New Name');
        $this->assertEquals('NEW NAME', $model->getAttribute('name')); // Using mutator

        $model->setAttribute('age', 25);
        $this->assertEquals(25, $model->getAttribute('age'));
    }

    public function testUnsetWillRemoveAttributeFromAttributesList()
    {
        $model = new ConcreteModelStub();
        $this->assertNotEmpty($model['age']);

        unset($model['age']);

        $this->assertFalse(
            isset($model['age'])
        );
    }

    public function testIssetWillReturnTrueForAttributeWhichExists()
    {
        $model = new ConcreteModelStub();
        $this->assertTrue(
            isset($model['name'])
        );
    }

    public function testIssetWillReturnFalseForAttributeWhichDoesntExistsAndNotMutated()
    {
        $model = new ConcreteModelStub();
        $this->assertFalse(
            isset($model['x_age'])
        );
    }

    public function testIssetWillReturnMutatedValueForAttributeWhichDoesntExistsAndMutated()
    {
        $model = new ConcreteModelStub();

        unset($model['name']);

        $this->assertIsString(
            $model['name']
        );
        $this->assertEquals('', $model['name']);
    }

    public function testArrayAccess()
    {
        $model = new ConcreteModelStub();
        $this->assertTrue(isset($model['name']));
        $this->assertEquals('TEST NAME', $model['name']); // Using mutator
        $model['name'] = 'New Name';
        $this->assertEquals('NEW NAME', $model['name']); // Using mutator
    }

    public function testJsonSerialization()
    {
        $model = new ConcreteModelStub();
        $json = $model->toJson();
        $this->assertJson($json);
        $this->assertStringContainsString('"name":"TEST NAME"', $json);
        $this->assertStringContainsString('"age":30', $json);
    }

    public function testHidden()
    {
        $model = new ConcreteModelStub(['name' => 'foo', 'age' => 'bar', 'id' => 'baz']);
        $model->setHidden(['age', 'id']);
        $array = $model->toArray();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('age', $array);
        $this->assertArrayNotHasKey('id', $array);
    }

    public function testVisible()
    {
        $model = new ConcreteModelStub(['name' => 'foo', 'age' => 'bar', 'id' => 'baz']);
        $model->setVisible(['name', 'id']);
        $array = $model->toArray();
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayNotHasKey('age', $array);
    }

    public function testFillableGuardedAttributes()
    {
        $model = new ConcreteModelStub();
        $model->fillable(['name']);
        $model->guard(['age']);

        $attributes = ['name' => 'New Name', 'age' => 25];
        $model->fill($attributes);

        $this->assertEquals(['name' => 'new name', 'age' => 30], $model->getAttributes());
    }

    public function testHydrate()
    {
        $items = [
            ['name' => 'Name1', 'age' => 20],
            ['name' => 'Name2', 'age' => 30]
        ];
        $models = ConcreteModelStub::hydrate($items);
        $this->assertCount(2, $models);
        $this->assertEquals('NAME1', $models[0]->name); // Using mutator
        $this->assertEquals('NAME2', $models[1]->name); // Using mutator
    }
}