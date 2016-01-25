<?php

use Bowtie\Grawler\Nodes\Attributes;

class AttributesTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    function it_can_get_and_attribute_from_subclass()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals('21-22-23',$attributesSubclass->get('id'));
    }

    /** @test */
    function it_returns_null_for_unavailable_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals(null,$attributesSubclass->get('body'));
    }

    /** @test */
    function it_can_set_a_valid_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $attributesSubclass->set('title','new title');
        $this->assertEquals('new title',$attributesSubclass->title);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_throw_an_exception_if_trying_to_set_an_invalid_attribute()
    {
        $attributesSubclass = new AttributeSubClass();
        $attributesSubclass->set('body','new body');
    }


    /** @test */
    function it_can_return_all_attributes_names()
    {
        $attributesSubclass = new AttributeSubClass();
        $this->assertEquals(['id','title'],$attributesSubclass->getKeys());
    }


}


class AttributeSubClass extends Attributes
{

    protected $attributes = [
        'id'    => '21-22-23',
        'title' => 'subclass title'
    ];
}