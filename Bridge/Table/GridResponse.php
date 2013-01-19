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

namespace Saturno\Bridge\Table;

use Saturno\Bridge\Table\Table;

abstract class GridResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    protected $table;

    public function __construct(Table $table, Array &$data = array())
    {
        $this->table = $table;
        parent::__construct($data);
    }

    /* @todo refactoring these names */

    public abstract  function setEntities(Array $content);

    public abstract  function getDataGrid();

    public abstract  function setTotal($total);
    public abstract  function getTotal();

    public abstract function  setShown($shown);
    public abstract function  getShown();
}
