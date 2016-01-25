<?php


use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\MediaCollection;

class MediaCollectionTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    function it_can_get_an_item_by_offset()
    {
        $image = new Image('http://example.com/image.png');
        $collection = new MediaCollection($image);
        $this->assertEquals($image,$collection->get(0));
    }

    /** @test */
    function it_returns_null_for_unavailable_attribute()
    {
        $collection = new MediaCollection();
        $this->assertEquals(null,$collection->get(0));
    }

    /** @test */
    function it_can_add_media_to_collection()
    {
        $collection = new MediaCollection();
        $collection->add(new Image('http://example.com/image.png'));
        $this->assertCount(1,$collection);
    }

    /** @test */
    function it_can_add_media_to_the_end_of_the_collection()
    {
        $collection = new MediaCollection();
        $collection->set(null,new Image('http://example.com/image.png'));

        $this->assertCount(1,$collection);
    }

    /** @test */
    function it_can_check_if_an_argument_is_an_assoc_array()
    {

        $collection = new MediaCollection();

        $reflection = new \ReflectionClass(get_class($collection));
        $method = $reflection->getMethod('isAssocArray');
        $method->setAccessible(true);

        $isAssoc = $method->invokeArgs($collection, $args = [['title' => 'one', 'body' => 'two']]);
        $this->assertTrue($isAssoc);

        $isAssoc = $method->invokeArgs($collection, $args = [['one','two']]);
        $this->assertFalse($isAssoc);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    function it_throw_an_exception_if_trying_to_set_a_none_media_instance_argument()
    {
        $attributesSubclass = new MediaCollection();
        $attributesSubclass->set(0,'invalid argument');
    }

}


