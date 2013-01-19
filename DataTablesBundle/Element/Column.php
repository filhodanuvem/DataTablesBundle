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
namespace Saturno\DataTablesBundle\Element;

class Column
{
    protected $access;

    protected $label;

    protected $settings;

    public function __construct($access, $label, Array $settings = array() )
    {
        $this->access = $access;
        $this->label  = $label;
        $this->settings = $settings;
    }

    public function getAccess()
    {
        return $this->access;
    }

    public function getName()
    {
        return $this->getAccess();
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function __toString()
    {
        return $this->label;
    }

}
