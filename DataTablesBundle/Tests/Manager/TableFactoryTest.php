<?php
/**
 *
 * @package Saturno::Table
 * @author Claudson Oliveira <claudsonweb@gmail.com>
 * @since 1.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saturno\DataTablesBundle\Tests;



class TableFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function atest_valid_instance_of_table()
    {
        /*$tableClass = $this->getMockForAbstractClass(
            'Saturno\\DataTablesBundle\\Interfaces\\Table',
            array(),
            'Acme\\DemoBundle\\Table\\FooTable'
        );*/
        $tableClass = $this->getMock('Acme\\DemoBundle\\Table\\FooTable');
        $parameter = 'AcmeDemoBundle:Foo';

        $args = $this->getArgsFactory();
        $factory = $this->getMockBuilder('\\Saturno\\DataTablesBundle\\Manager\\TableFactory')
                        ->setConstructorArgs($args)
                        ->getMock();
        $factory->expects($this->once())
                ->method('getTableClassName')
                ->with($parameter)
                ->will($this->returnValue('Acme\\DemoBundle\\Table\\FooTable'));
        $factory->expects($this->once())
                ->method('getTable')
                ->with($parameter)
                ->will($this->returnValue($tableClass));
        //var_dump($factory->getTable($parameter) instanceof Saturno\DataTablesBundle\Interfaces\Table);
        //$this->assertInstanceOf('Saturno\\DataTablesBundle\\Interfaces\\Table', $factory->getTable($parameter));


    }


    /**
     * @dataProvider providerIdentifiersAndTableNames
     * @param string $identifier
     * @param string $namespace
     */
    public function test_convert_identifier_to_table_name($bundleName, $bundleNamespace, $identifier, $namespace)
    {

        $template = $this->getMock('\Twig_Environment');
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel', array('getBundles'))
                       ->setConstructorArgs(array(
                            'dev', true
                        ))
                        ->getMock();
        $kernel->expects($this->any())
               ->method('getBundles')
               ->will($this->returnValue(array(
                    $bundleName => $bundleNamespace
                )));

        $factory = new \Saturno\DataTablesBundle\Manager\TableFactory($template, $kernel);
        $reflection = new \ReflectionClass($factory);
        $method = $reflection->getMethod('getTableClassName');
        $method->setAccessible(true);

        $this->assertEquals($namespace, $method->invoke($factory, $identifier));
    }


    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider providerNamesUnformated
     * @param $name
     */
    public function testFindAtableWithAnUnformatedName($bundleName, $bundleNamespace, $identifier, $namespace)
    {
         $template = $this->getMock('\Twig_Environment');
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel', array('getBundles'))
                       ->setConstructorArgs(array(
                            'dev', true
                        ))
                        ->getMock();
        $kernel->expects($this->any())
               ->method('getBundles')
               ->will($this->returnValue(array(
                    $bundleName => $bundleNamespace
                )));

        $factory = new \Saturno\DataTablesBundle\Manager\TableFactory($template, $kernel);
        $reflection = new \ReflectionClass($factory);
        $method = $reflection->getMethod('getTableClassName');
        $method->setAccessible(true);

        $this->assertEquals($namespace, $method->invoke($factory, $identifier));
    }

    /**
     * @expectedException \UnexpectedValueException
     * @dataProvider providerIdentifiersAndTableNames
     * @param $bundleName
     * @param $bundleNamespace
     * @param $identifier
     * @param $namespace
     */
    public function test_find_table_in_one_bundle_not_found($bundleName, $bundleNamespace, $identifier, $namespace)
    {
        $template = $this->getMock('\Twig_Environment');
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel', array('getBundles'))
            ->setConstructorArgs(array(
            'dev', true
        ))
            ->getMock();
        $kernel->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue(array(
            'EverFooBundle' => 'Ever\FooBundle'
        )));

        $factory = new \Saturno\DataTablesBundle\Manager\TableFactory($template, $kernel);
        $reflection = new \ReflectionClass($factory);
        $method = $reflection->getMethod('getTableClassName');
        $method->setAccessible(true);

        $this->assertEquals($namespace, $method->invoke($factory, $identifier));
    }

    /**
     * @dataProvider providerIdentifiersAndTableNames
     * @param $bundleName
     * @param $bundleNamespace
     * @param $identifier
     * @param $namespace
     */
    public function test_if_get_a_table_instance($bundleName, $bundleNamespace, $identifier, $namespace)
    {
        $template = $this->getMock('\Twig_Environment');
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel')
            ->setConstructorArgs(array(
            'dev', true
        ))->getMock();

        $factory = $this->getMockBuilder('\Saturno\DataTablesBundle\Manager\TableFactory')
                        ->setConstructorArgs(array($template, $kernel))
                        ->getMock();
        $factory->expects($this->any())
                ->method('getTable')
                ->with($identifier)
                ->will($this->returnValue($this->getMock($namespace)));

        $factory->expects($this->any())
            ->method('getTableClassName')
            ->with($identifier)
            ->will($this->returnValue($namespace));

        $this->assertInstanceOf($namespace, $factory->getTable($identifier));

    }

    public function providerIdentifiersAndTableNames()
    {
        return array(
            array('AcmeFooBundle', 'Acme\FooBundle\AcmeFooBundle', 'AcmeFooBundle:Bar','Acme\FooBundle\DataTable\BarTable'),
            array('SaturnoAcmeFooBundle', 'Saturno\Acme\FooBundle\SaturnoAcmeFooBundle', 'SaturnoAcmeFooBundle:Bar','Saturno\Acme\FooBundle\DataTable\BarTable'),
            array('FooBarBazBrazilBundle', 'Foo\Bar\Baz\BrazilBundle\FooBarBazBrazilBundle', 'FooBarBazBrazilBundle:Cloud','Foo\Bar\Baz\BrazilBundle\DataTable\CloudTable'),
        );
    }

    public function providerNamesUnformated()
    {
        return array(
            array('AcmeFooBundle', 'Acme\FooBundle\AcmeFooBundle', 'AcmeFooBundle','Acme\FooBundle\DataTable\BarTable'),
            array('SaturnoAcmeFooBundle', 'Saturno\Acme\FooBundle\SaturnoAcmeFooBundle', 'SaturnoAcmeFooBundle','Saturno\Acme\FooBundle\DataTable\BarTable'),
            array('FooBarBazBrazilBundle', 'Foo\Bar\Baz\BrazilBundle\FooBarBazBrazilBundle', 'FooBarBazBrazilBundle','Foo\Bar\Baz\BrazilBundle\DataTable\CloudTable'),
        );
    }


    public function getArgsFactory()
    {
        $template = $this->getMock('\Twig_Environment');
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel', array('getBundles'))
            ->setConstructorArgs(array(
            'dev', true
        ))
            ->getMock();

        return array($template, $kernel);
    }
}
