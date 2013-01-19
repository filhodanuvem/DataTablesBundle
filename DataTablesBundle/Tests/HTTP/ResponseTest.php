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

namespace Saturno\DataTablesBundle\Tests\HTTP;

use Saturno\DataTablesBundle\Tests\Fixtures\User;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function test_grid_response_empty()
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);

        $dataGrid = $response->getDataGrid();
        $expected = json_encode($dataGrid);

        $this->assertEquals($expected, $response->getContent());
    }

    public function test_grid_response_with_simple_entity()
    {
        $template = $this->getMock('Twig_Environment');
        $userTable = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);

        $response = new \Saturno\DataTablesBundle\HTTP\Response($userTable);
        $dataGrid = $response->getDataGrid();
        $dataGrid['aaData'] = array(
            array(1, 'Claudson Oliveira', '1990-05-28'),
            array(2, 'Jean Pimentel', '1988-08-10'),
        );

        $expected = json_encode($dataGrid);
        $response->setEntities(array(
            new User(1, 'Claudson Oliveira', '1990-05-28'),
            new User(2, 'Jean Pimentel', '1988-08-10'),
        ));

        $this->assertEquals($expected, $response->getContent());
    }

    /**
     * @dataProvider providerValidTotals
     * @param mixed $total
     */
    public function test_if_total_is_correct($total)
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);
        $response->setTotal($total);

        $this->assertEquals($total, $response->getTotal());
    }

    /**
     *
     * @dataProvider providerInvalidTotals
     * @param mixed $total
     */
    public function test_invalid_totals($total)
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);

        try {
            $response->setTotal($total);
        } catch (\InvalidArgumentException $e) {
            return;
        } catch (\UnexpectedValueException $e) {
            return;
        }
    }

    /**
     * @dataProvider providerValidTotals
     * @param mixed $total
     */
    public function test_valid_total_shown($shown)
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);
        $response->setShown($shown);

        $this->assertEquals($shown, $response->getShown());
    }

    /**
     * @dataProvider providerShownGreaterThenTotal
     * @expectedException UnexpectedValueException
     * @param mixed $total
     * @param mixed $shown
     */
    public function test_if_total_shown_is_greater_then_total_of_entities($shown, $total)
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);
        $response->setTotal($total);
        $response->setShown($shown);
    }

    /**
     *
     * @dataProvider providerInvalidTotals
     * @param mixed $shown
     */
    public function test_invalid_total_shown($shown)
    {
        $table = $this->getMockForAbstractClass('Saturno\\Bridge\\Table\\Table');
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);
        try{
            $response->setShown($shown);
        } catch (\InvalidArgumentException $e) {
            return;
        } catch (\UnexpectedValueException $e) {
            return;
        }
    }



    public function providerValidTotals()
    {
        return array(
            array(0),
            array('1'),
        );
    }

    public function providerInvalidTotals()
    {
        return array(
            array('brazil'),
            array(new \stdClass),
            array(array()),
            array(-1),
            array('-200'),
            array(-1.8),
            array('3.14')
        );
    }

    public function providerShownGreaterThenTotal()
    {
        return array(
            array(PHP_INT_MAX,1),
            array('999',666),
            array(10,8),
        );
    }



}
