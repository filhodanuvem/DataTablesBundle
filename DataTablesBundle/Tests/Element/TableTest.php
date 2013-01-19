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

namespace Saturno\DataTablesBundle\Tests\Element;

use Saturno\DataTablesBundle\Element\Column;

class TableTest extends \PHPUnit_Framework_testCase
{

    public function test_get_table_name()
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $reflect = new \ReflectionClass($table);
        $method = $reflect->getMethod('getName');
        $method->setAccessible(true);

        $expected = 'User';

        $this->assertEquals($expected, $method->invoke($table));
    }


    public function test_labels_of_table()
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $columns = array_values($table->getColumns());
        $column = $columns[0];

        $expected = 'Code';

        $this->assertEquals($expected, $column->getLabel());

    }

    public function test_labels_of_table_with_toString()
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $columns = array_values($table->getColumns());
        $column = $columns[0];

        $expected = 'Code';

        $this->assertEquals($expected, $column);

    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider providerNotTemplates
     * @param mixed $wrongTemplate
     */
    public function test_setting_not_template($wrongTemplate)
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $table->setTemplate($wrongTemplate);

    }

    public function test_change_template()
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $this->assertInstanceOf('\Saturno\DataTablesBundle\Element\Table', $table->setTemplate('fooTemplate.html.twig'));
    }

    /**
     * @dataProvider providerSettings
     * @param $key
     */
    public function test_if_settings_default_contains_basic_info($key)
    {
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $reflect = new \ReflectionClass($table);
        $method = $reflect->getMethod('getSettings');
        $method->setAccessible(true);

        $this->assertArrayHasKey($key, $method->invoke($table));
    }

    /**
     * @dataProvider providerCorrectAddColumns
     */
    public function test_if_has_column_insert_data_into_columns_array($property, $label, $config, $expected)
    {
        $table = $this->getTable();
        $table->hasColumn($property, $label, $config);
        $this->assertEquals($expected, $table->getColumns(), 'GetColumns is failed');
    }

    /**
     * @dataProvider providerIndexColumns
     * @param $index
     * @param $expected
     */
    public function test_valid_indexes_as_names_in_columns($index, $expected)
    {
        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $this->assertEquals($expected, $table->getColumnName($index));

    }

    public function test_valid_return_of_content_table()
    {
        $user1 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(1,'Joseph','2013-05-23');
        $user2 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(2,'Hellena','1988-06-27');

        $expected = array(
            array(1,'Joseph','2013-05-23'),
            array(2,'Hellena','1988-06-27')
        );

        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $table->setBody(array(
            $user1,
            $user2,
        ));

        $this->assertEquals($expected, $table->getBody());
    }

    public function test_insert_valid_extras_settings()
    {
        $extra = array(
            'url' => 'saturno_datatables_foo',
        );

        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $table->setSettings($extra);
        $reflect = new \ReflectionClass($table);
        $method = $reflect->getMethod('getSettings');
        $method->setAccessible(true);
        $data = $method->invoke($table);
        $this->assertEquals($extra, $data['config']);
    }


    // rewrite this test
    public function testRenderJavascript()
    {

        $templateObject = $this->getMock('\Twig_TemplateInterface');
        $template = $this->getMockBuilder('\Twig_Environment')->getMock();
        $template->expects($this->any())
                 ->method('render')
                 ->will($this->returnValue($templateObject));

        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $this->assertInstanceOf('\Twig_TemplateInterface',$table->createJavascript());
    }

    // rewrite this test
    public function testRenderView()
    {

        $templateObject = $this->getMock('\Twig_TemplateInterface');
        $template = $this->getMockBuilder('\Twig_Environment')->getMock();
        $template->expects($this->any())
            ->method('render')
            ->will($this->returnValue($templateObject));

        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $this->assertInstanceOf('\Twig_TemplateInterface',$table->createView());
    }

    // @todo to test columns extras that does linked to entities

    // providers

    public function providerSettings()
    {
        return array(
            array('name'),
            array('columns'),
            array('body'),
            array('config'),
        );
    }

    public function providerCorrectAddColumns()
    {

        return array(
            array('foo','Bar',array(), array('foo' => new Column('foo','Bar'))),
            array('Claudson','Cloudson', array('anything' => new \stdClass), array('Claudson'=>new Column('Claudson','Cloudson',array('anything' => new \stdClass))))
        );
    }

    public function providerIndexColumns()
    {
        return array(
            array('0','id'),
            array(2,'date'),
        );
    }

    public function providerNotTemplates()
    {
        return array(
            array(0),
            array(new \stdclass)
        );
    }

    // getting mock
    public function getTable()
    {
        $template  = $this->getMockForAbstractClass('\Twig_Environment');
        /* @var $table \Saturno\DataTablesBundle\Element\Table */
        $table = $this->getMockBuilder('Saturno\DataTablesBundle\Element\Table')
             ->setConstructorArgs( array($template))
             ->getMockForAbstractClass();

        return $table;
    }
}
