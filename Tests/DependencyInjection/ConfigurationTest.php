<?php

namespace WellsFargo\ACHBundle\Tests\DependencyInjection;

use WellsFargo\ACHBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $config = $this->process(array());
        $this->assertEmpty($config);
    }
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testAddingWellsFargoKeyRequiresValues()
    {
        $arr = array(
            array("wells_fargo_ach" => "~"),
        );
        $config = $this->process($arr);
    }

    /**
     * Takes in an array of configuration values and returns the processed version
     *
     * @param array $config
     * @return array
     */
    protected function process($config)
    {
        $processor = new Processor();
        return $processor->processConfiguration(new Configuration(), $config);
    }
}