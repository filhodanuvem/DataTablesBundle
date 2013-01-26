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
namespace Saturno\DataTablesBundle\HTTP;

use Saturno\Bridge\Table\Table;

class Response extends \Saturno\Bridge\Table\GridResponse
{

    protected $dataGrid = array(
        'aaData' => array(),
        'iTotalDisplayRecords' => 0,
        'iTotalRecords' => PHP_INT_MAX,
        'sEcho' => 0,
    );

    /**
     * @param \Saturno\Bridge\Table\Table $table
     * @param array $data
     */
    public function __construct(Table &$table, Array $data = array())
    {
        $this->counterRequests();
        $this->dataGrid = array_merge($this->dataGrid, $data);
        parent::__construct($table, $this->dataGrid);
        $this->updateDataGrid();
    }


    /**
     * @param array $content
     */
    public function setEntities(Array $content)
    {
        $this->table->setBody($content);
        $this->updateDataGrid();
    }

    /**
     * returns the array with all content that will be json
     * @return array
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }

    /**
     * @param $total
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function setTotal($total)
    {
        if (!is_numeric($total) || is_float($total)) {
            throw new \InvalidArgumentException("Trying set is not a valid value to total of entities on response");
        }

        if ($total < 0) {
            throw new \UnexpectedValueException(" total of entities on response can't be less then zero ");
        }

        $this->dataGrid['iTotalRecords'] = $total;
        $this->updateDataGrid();
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->dataGrid['iTotalRecords'];
    }

    /**
     * @param $shown
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     */
    public function setShown($shown)
    {
        if (!is_numeric($shown) || is_float($shown)) {
            throw new \InvalidArgumentException("total shown is not a valid value to total of entities shown on response");
        }

        if ($shown > $this->getTotal() || $shown < 0) {
            throw new \UnexpectedValueException(" total of values shown  is greater then total");
        }

        $this->dataGrid['iTotalDisplayRecords'] = $shown;
        $this->updateDataGrid();
    }

    /**
     * @return int
     */
    public function getShown()
    {
        return $this->dataGrid['iTotalDisplayRecords'];
    }

    /**
     * with the data in $dataGrid, we update the object itself
     */
    private function updateDataGrid()
    {
        $body = $this->table->getBody();
        $this->dataGrid['aaData'] = $body;
        $this->setData($this->dataGrid);
    }

    /**
     * this is very hard code, the datables needs know at how request number
     * it is. We could receive request object, but its no sense =(
     */
    private function counterRequests()
    {
        $this->dataGrid['sEcho'] = (array_key_exists('sEcho', $_REQUEST)) ? $_REQUEST['sEcho'] : 0;
    }

}
