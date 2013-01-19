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

use Symfony\Component\HttpFoundation\Request as httpRequest;
use Saturno\Bridge\Table\Table;

class Request implements \Saturno\Bridge\Table\GridRequest
{
    /**
     * @var Table $table origin of the request
     */
    protected $table;

    public $variables;

    protected  $request;
    const LIMIT_DEFAULT = 10;

    public function __construct(httpRequest $request)
    {
        $this->request = $request;
        $this->variables  = array(
            'limit'    => self::LIMIT_DEFAULT,
            'orderBy'  => null,
            'orderDir' => 'desc',
            'like'     => null,
            'search'   => null,
            'offset'   => 0,
        );

    }

    protected function generateVariables(httpRequest $request)
    {
        /* @todo change to choice between post or get method */
        $requestArray = array_merge($request->query->all(), $request->request->all());

        foreach ($requestArray as $key => $value) {
            list($newKey, $newValue) = $this->converter($key, $value);
            $this->variables[$newKey] = $newValue;
        }
    }


    public function format(Table $table)
    {
        $this->table = $table;
        $this->generateVariables($this->request);

    }

    /**
     * @todo to give support the many orderning using iSortCol_0, iSortCol_1...
     * @param string $key
     * @param string $value
     * @return array
     */
    private function converter($key, $value)
    {
        if (!($this->table instanceof \Saturno\Bridge\Table\Table)) {
            throw new \Saturno\DataTablesBundle\Exceptions\TableNotFoundException('Request without entity');
        }

        if ($key == 'iDisplayLength' && $value >= 0 ) {
            return array('limit', $value);
        }

        if ($key == 'iDisplayStart'  && $value >= 0 ) {
            return array('offset', $value);
        }

        if ($key == 'sSortDir_0' && $value) {
            return array('orderDir',$value);
        }

        if ($key == 'sSearch' && $value) {
            return array('like', $value);
        }

        if ($key == 'iSortCol_0' && $value) {
            return array(
                'orderBy',
                $this->table->getColumnName($value),
            );
        }

        return array($key, $value);
    }

    public function all()
    {
        return $this->variables;
    }

    public function get($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException("key is not a string");
        }

        if (!array_key_exists($key, $this->variables)) {
            throw new \UnexpectedValueException("key is not valid ");
        }

        return  $this->variables[$key];
    }
}
