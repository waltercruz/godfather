<?php

namespace PUGX\GodfatherBundle\Tests\Container\DependencyInjection;

use PUGX\Godfather\Container\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Definition;

class CompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithoutProviderDefinition()
    {
        $menuPass = new CompilerPass();

        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array()));

        $this->assertNull($menuPass->process($containerBuilderMock));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProcessWithEmptyClass()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array('id' => array('tag1' => array('class' => '')))));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

    public function testProcessWithClassAndName()
    {
        $definitionMock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();
        $definitionMock->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addStrategy'), $this->isType('array'));

        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array('id' => array('tag1' => array('context_key' => 'key', 'context_name' => 'name')))));
        $containerBuilderMock->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('godfather'))
            ->will($this->returnValue($definitionMock));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

    public function testProcessWithMultipleInstance()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(
                array(
                    'id2' => array('tag2' => array('instance' => 'instance2', 'context_key' => 'key', 'context_name' => 'name'))
                )));

        $containerBuilderMock->expects($this->once())
            ->method('hasDefinition')
            ->will($this->returnValue(false));

        $containerBuilderMock->expects($this->any())
            ->method('setDefinition')
            ->with($this->equalTo('godfather.instance2'));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

}
