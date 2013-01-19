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

namespace Saturno\DataTablesBundle\Tests\Fixtures;

class User
{
    protected $id;
    protected $name;
    protected $date;

    public function __construct($id, $name, $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    // we use isData instead of get to the tests
    public function isDate()
    {
        return $this->date;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }


}
