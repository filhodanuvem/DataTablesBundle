<?php
/**
 *  @package Saturno::Table
 *  @author  Claudson Oliveira <claudsonweb@gmail.com>
 *  @since   1.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saturno\DataTablesBundle\Tests\HTTP;

class RequestTest extends  \PHPUnit_Framework_TestCase
{

    /**
     * @param $GET
     * @param $expected
     */
    public function test_if_grid_request_will_format_simple_GET()
    {
        // dados iniciais
        $GET = array(
            'iDisplayLength' => 20,
            'iDisplayStart' => 5,
        );

        $expected = array(
            'limit' => 20,
            'offset' => 5,
            'like' => null,
            'search' => null,
            'orderBy' => null,
            'orderDir' => 'desc',
        );

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = $this->getMockBuilder('\Saturno\DataTablesBundle\Element\Table')
            ->setConstructorArgs( array($template))
            ->getMockForAbstractClass();

        $request = new \Symfony\Component\HttpFoundation\Request($GET);

        $gridRequest = new \Saturno\DataTablesBundle\HTTP\Request($request);
        $gridRequest->format($table);
        
        // test
        $this->assertEquals($expected, $gridRequest->all());
    }

    public function test_if_grid_request_will_format_columnsName()
    {
        $GET = array(
            'iSortCol_0' => 1,
            'sSearch'    => 'foo',
        );

        $expected = $expected = array(
            'limit' => 10,
            'offset' => 0,
            'like' => 'foo',
            'search' => null,
            'orderBy' => 'name',
            'orderDir' => 'desc',
        );
        $request = new \Symfony\Component\HttpFoundation\Request($GET);

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);


        $gridRequest = new \Saturno\DataTablesBundle\HTTP\Request($request);
        $gridRequest->format($table);

        $this->assertEquals($expected, $gridRequest->all());

    }

    /**
     * @dataProvider convertionsGET
     * @param $GET
     * @param $expected
     */
    public function test_convert_keys($GET,$expected,$value)
    {

        $request = new \Symfony\Component\HttpFoundation\Request(array($GET => $value));

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $gridRequest = new \Saturno\DataTablesBundle\HTTP\Request($request);
        $gridRequest->format($table);
        $reflectionClass = new \ReflectionClass($gridRequest);
        $method = $reflectionClass->getMethod('converter');
        $method->setAccessible(true);

        $this->assertEquals(array($expected, $value), $method->invoke($gridRequest, $GET, $value));

    }

    public function convertionsGET()
    {
        return array(
            array('iDisplayLength','limit',20),
            array('sSearch','like','foo'),
            array('iDisplayStart','offset',4),
            array('sSortDir_0','orderDir',4),
            array('foo','foo','bar'),
        );
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function test_if_catch_exception_accessing_unknow_column()
    {
        $GET = array(
            'iSortCol_0' => 100,
            'sSearch'    => 'foo',
        );

        $request = new \Symfony\Component\HttpFoundation\Request($GET);

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $gridRequest = new \Saturno\DataTablesBundle\HTTP\Request($request);
        $gridRequest->format($table);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_if_catch_exception_accessing_column_not_numeric()
    {
        $GET = array(
            'iSortCol_0' => 'foo',
        );

        $request = new \Symfony\Component\HttpFoundation\Request($GET);

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $gridRequest = new \Saturno\DataTablesBundle\HTTP\Request($request);
        $gridRequest->format($table);
    }

    private function getMethodFormatAsPublic()
    {
        $reflectionClass = new \ReflectionClass('\Saturno\DataTablesBundle\HTTP\Request');
        $method = $reflectionClass->getMethod('format');
        $method->setAccessible(true);

        return $method;
    }
}
