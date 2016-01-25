<?php

use Bowtie\Grawler\Nodes\Collection;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    function it_can_return_an_iterator_instance()
    {
        $attributesSubclass = new CollectionSubClass();
        $this->assertInstanceOf(ArrayIterator::class, $attributesSubclass->getIterator());
    }

    /** @test */
    function offset_get_is_an_alias_for_get()
    {
        $mock = $this->getMock(CollectionSubClass::class, ['get']);
        $mock->expects($this->once())->method('get');
        $mock->offsetGet('title');
    }

    /** @test */
    function offset_set_is_an_alias_for_set()
    {
        $mock = $this->getMock(CollectionSubClass::class, ['set']);
        $mock->expects($this->once())->method('set');
        $mock->offsetSet('title', 'new title');
    }

    /** @test */
    function it_can_set_items_using_magic_methods()
    {
        $mock = $this->getMock(CollectionSubClass::class, ['set']);
        $mock->add(['title' => 'one', 'body' => 'two']);
        $mock->expects($this->once())->method('set');
        $mock->title = 'three';
    }

    /** @test */
    function it_can_get_items_using_magic_methods()
    {
        $mock = $this->getMock(CollectionSubClass::class, ['get']);
        $mock->add(['title' => 'one', 'body' => 'two']);
        $mock->expects($this->once())->method('get');
        $mock->title;
    }

    /** @test */
    function it_can_check_if_an_argument_is_an_assoc_array()
    {

        $collection = new CollectionSubClass();

        $reflection = new \ReflectionClass(get_class($collection));
        $method = $reflection->getMethod('isAssocArray');
        $method->setAccessible(true);

        $isAssoc = $method->invokeArgs($collection, $args = [['title' => 'one', 'body' => 'two']]);
        $this->assertTrue($isAssoc);

        $isAssoc = $method->invokeArgs($collection, $args = [['one','two']]);
        $this->assertFalse($isAssoc);
    }

    /** @test */
    function it_can_get_all_items_keys()
    {
        $collection = new CollectionSubClass(['title' => 'one', 'body' => 'two']);

        $this->assertEquals(['title','body'], $collection->getKeys());
    }


    /** @test */
    function it_can_return_all_items_as_array()
    {
        $collection = new CollectionSubClass(['title' => 'one', 'body' => 'two']);

        $this->assertEquals(['title' => 'one', 'body' => 'two'], $collection->asArray());
        $this->assertEquals(['title' => 'one', 'body' => 'two'], $collection->all());

    }

    /** @test */
    function offset_unset_is_an_alias_for_to_null()
    {
        $collection = new CollectionSubClass(['one', 'two']);

        $collection->offsetUnset(0);

        $this->assertCount(1, $collection);
    }

}


class CollectionSubClass extends Collection
{


    public function add($items)
    {
        $this->items = $items;
    }

    public function get($offset)
    {
    }

    public function set($offset, $value)
    {
    }
}