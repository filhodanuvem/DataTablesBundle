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

class Body
{
    protected $rows;

    protected $father;

    public function __construct(Table $table)
    {
        $this->rows = array();
        $this->father = $table;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function addRow($entity)
    {
        $columns = $this->father->getColumns();
        $row = array();
        foreach ($columns as $column) {
           $row[] = $this->findValue($entity, $column->getAccess());
        }

        $this->rows[] = $row;

        return $this;
    }

    protected function findValue($entity, $access)
    {

        if (strstr($access, '.') !== false) {
            list($access, $rest) = explode('.', $access, 2);
            $child = $this->getOrIs($entity, $access);
            if (!is_object($child)) {
                $class = get_class($entity);
                throw new \UnexpectedValueException("'Get' or 'is' method on class {$class} not returns a object");
            }

            return $this->findValue($child, $rest);
        }

        return $this->getOrIs($entity, $access);
    }

    private function getOrIs($entity, $access){
        $method = 'get'.ucfirst($access);
        if (!method_exists($entity, $method)) {
            $method = 'is'.ucfirst($access);
            if (!method_exists($entity, $method)) {
                $class = get_class($entity);
                throw new \UnexpectedValueException("Does not exists method 'get' or 'is' to attributte {$access} in class {$class} ");
            }
        }

        return $entity->$method();
    }

}
