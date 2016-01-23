<?php

use Bowtie\Grawler\Config\ConfigAccess;
use Bowtie\Grawler\Config\Config;

class ConfigAccessTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_always_returns_confing_instance()
    {
        $classWithConfigAccess = new ClassWithConfigAccess();

        $this->assertInstanceOf(Config::class,$classWithConfigAccess->config());
    }

    /** @test */
    public function it_creates_a_config_instance_if_none_is_given()
    {
        $classWithConfigAccess = new ClassWithConfigAccess();

        $classWithConfigAccess->loadConfig();

        $this->assertInstanceOf(Config::class,PHPUnit_Framework_Assert::readAttribute($classWithConfigAccess, "config"));
    }

    /** @test */
    public function it_build_a_config_instance_from_array()
    {
        $classWithConfigAccess = new ClassWithConfigAccess();

        $classWithConfigAccess->loadConfig(['youtubeKey'=>'123456']);

        $this->assertEquals(new Config(['youtubeKey'=>'123456']),PHPUnit_Framework_Assert::readAttribute($classWithConfigAccess, "config"));

        $this->assertEquals('123456', $classWithConfigAccess->config()->get('youtubeKey') );
    }


    /** @test */
    public function it_will_return_the_same_config_object_if_sent_through_config_attribute()
    {
        $classWithConfigAccess = new ClassWithConfigAccess();

        $classWithConfigAccess->loadConfig(new Config(['youtubeKey'=>'123456']));

        $this->assertEquals('123456', $classWithConfigAccess->config()->get('youtubeKey') );
    }


}


class ClassWithConfigAccess {
    use ConfigAccess;
}