<?php

use Bowtie\Grawler\Nodes\Attributes;

class AttributesTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    function it_can_get_and_attribute_from_subclass()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals('21-22-23',$attributesSubclass->getAttribute('id'));
    }

    /** @test */
    function it_returns_null_for_unavailable_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals(null,$attributesSubclass->getAttribute('body'));
    }

    /** @test */
    function it_can_set_a_valid_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals('new title',$attributesSubclass->setAttribute('title','new title'));
    }

    /**
     * @test
     * @expectedException LogicException
     */
    function it_throw_an_exception_if_trying_to_set_an_invalid_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $attributesSubclass->setAttribute('body','new body');
    }


    /** @test */
    function it_can_return_all_attributes_names()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals(['id','title'],$attributesSubclass->getAttributesNames());
    }

    /** @test */
    function it_can_return_all_attributes_as_array()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals([
            'id'    => '21-22-23',
            'title' => 'subclass title'
        ],$attributesSubclass->asArray());

        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals([
            'id'    => '21-22-23',
            'title' => 'subclass title'
        ],$attributesSubclass->all());
    }

    /** @test */
    function it_can_return_the_number_attributes()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals(2,$attributesSubclass->count());
    }

    /** @test */
    function it_can_return_an_iterator_instance()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertInstanceOf(ArrayIterator::class,$attributesSubclass->getIterator());
    }

    /** @test */
    function it_returns_true_if_the_offset_exits()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertTrue($attributesSubclass->offsetExists('id'));
    }

    /** @test */
    function it_returns_false_if_the_offset_is_not_available()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertFalse($attributesSubclass->offsetExists('body'));
    }


    /** @test */
    function offset_get_is_an_alias_for_getAttribute()
    {
        $mock = $this->getMock(AttributeSubClass::class,['getAttribute']);
        $mock->expects($this->once())->method('getAttribute');
        $mock->offsetGet('title');
    }

    /** @test */
    function offset_set_is_an_alias_for_setAttribute()
    {
        $mock = $this->getMock(AttributeSubClass::class,['setAttribute']);
        $mock->expects($this->once())->method('setAttribute');
        $mock->offsetSet('title','krekib');
    }

    /** @test */
    function offset_unset_is_an_alias_for_setAttribute_to_null()
    {
        $mock = $this->getMock(AttributeSubClass::class,['setAttribute']);
        $mock->expects($this->once())->method('setAttribute')->with('title');
        $mock->offsetUnset('title');
    }
}


class AttributeSubClass extends Attributes
{

    protected $attributes = [
        'id'    => '21-22-23',
        'title' => 'subclass title'
    ];

}