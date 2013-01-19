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

use Saturno\DataTablesBundle\Tests\Fixtures\Product;
use Saturno\DataTablesBundle\Tests\Fixtures\InvalidProduct;

class BodyTest extends \PHPUnit_Framework_TestCase
{


    public function test_add_simples_rows()
    {
        $user1 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(1, 'Claudson Oliveira', '1990-05-28');
        $user2 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(2, 'Jean Pimentel', '1988-08-25');
        $user3 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(3, 'Foo', '1988-08-25');
        $user4 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(4, 'Bar', '2012-03-04');
        $expected = array(
            array(1, 'Claudson Oliveira', '1990-05-28'),
            array(2, 'Jean Pimentel', '1988-08-25'),
            array(3, 'Foo', '1988-08-25'),
            array(4, 'Bar', date('2012-03-04')),
        );
        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\UserTable($template);
        $body = new \Saturno\DataTablesBundle\Element\Body($table);

        for ($i = 1; $i<=4 ; $i++) {
            $user = ${'user'.$i};
            $body->addRow($user);
        }

        $this->assertEquals($expected, $body->getRows());

    }

    public function test_add_rows_with_compound_attributes()
    {
        $user1 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(1,'Joseph','2013-05-23');
        $user2 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(2,'Hellena','1988-06-27');
        $product1 = new Product(1, 'xbox', $user1);
        $product2 = new Product(2, 'barbie', $user2);

        $expected = array(
            array(1, 'xbox', 'Joseph'),
            array(2, 'barbie','Hellena'),
        );

        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\ProductTable($template);
        $body = new \Saturno\DataTablesBundle\Element\Body($table);

        for ($i = 1; $i<=2 ; $i++) {
            $product = ${'product'.$i};
            $body->addRow($product);
        }
        $this->assertEquals($expected, $body->getRows());

    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function test_access_invalid_compound_attribute()
    {
        $user1 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(1,'Joseph','2013-05-23');
        $user2 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(2,'Hellena','1988-06-27');
        $product1 = new Product(1, 'xbox', $user1);
        $product2 = new Product(2, 'barbie', $user2);

        $expected = array(
            array(1, 'xbox', 'Joseph'),
            array(2, 'barbie','Hellena'),
        );

        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\InvalidProductTable($template);
        $body = new \Saturno\DataTablesBundle\Element\Body($table);

        for ($i = 1; $i<=2 ; $i++) {
            $product = ${'product'.$i};
            $body->addRow($product);
        }
        $this->assertEquals($expected, $body->getRows());

    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function test_access_invalid_compound_attribute_with_not_object()
    {
        $user1 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(1,'Joseph','2013-05-23');
        $user2 = new \Saturno\DataTablesBundle\Tests\Fixtures\User(2,'Hellena','1988-06-27');
        $product1 = new InvalidProduct(1, 'xbox', $user1);
        $product2 = new InvalidProduct(2, 'barbie', $user2);

        $expected = array(
            array(1, 'xbox', 'Joseph'),
            array(2, 'barbie','Hellena'),
        );

        $template = $this->getMock('\Twig_Environment');
        $table = new \Saturno\DataTablesBundle\Tests\Fixtures\InvalidProductTable($template);
        $body = new \Saturno\DataTablesBundle\Element\Body($table);

        for ($i = 1; $i<=2 ; $i++) {
            $product = ${'product'.$i};
            $body->addRow($product);
        }
        $this->assertEquals($expected, $body->getRows());

    }

}
