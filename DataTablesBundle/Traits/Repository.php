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

namespace Saturno\DataTablesBundle\Traits;

use Doctrine\ORM\QueryBuilder;
use Saturno\DataTablesBundle\HTTP\Request;

trait Repository
{

    protected function  filter(QueryBuilder &$qb, Request $request)
    {
        $this->setLeftJoin($qb, $request);
        $this->setOrderBy($qb, $request);
        $this->setLimit($qb, $request);

        return $qb;
    }

    protected  function setLimit(QueryBuilder &$qb, Request $request)
    {
        try {
            $qb->setFirstResult($request->get('offset'));
            $qb->setMaxResults($request->get('limit'));
        } catch (\UnexpectedValueException $e) {
            $qb->setFirstResult(0);
            $qb->setMaxResults(PHP_INT_MAX);
        }

        return $this;
    }

    protected function setLeftJoin(QueryBuilder &$qb, Request $request, $alias = null)
    {
        /**
        * case the table be orderning by other object, we need a join 
        */ 
        
        $field = $request->get('orderBy');
        $this->insertLeftJoin($qb, $field);   
        return $qb;
    }

    private function insertLeftJoin(QueryBuilder &$qb, $field, $alias = null)
    {
        if (strstr($field, '.') === false) {
            return $qb;
        }   
        list($object, $field) = explode('.', $field, 2);
        
        $alias = (is_null($alias)) ? $this->getAlias($qb) : $alias;
        $qb->leftJoin($alias.'.'.$object, $object);
        $this->insertLeftJoin($qb, $field, $alias);
        
    }

    protected  function setOrderBy(QueryBuilder &$qb, Request $request)
    {
        
        $field = $request->get('orderBy');
        $access = $field;
        if (strstr($field,'.') === false) {
            $access = $this->getAlias($qb).'.'.$field;
        }

        if ($qb->getDQLPart('orderBy')) {
            $qb->addOrderBy($access, $request->get('orderDir'));

            return $this;
        }
        $qb->orderBy($access, $request->get('orderDir'));
        return $this;
    }

    private function getAlias(QueryBuilder &$qb)
    {
        $aliases = $qb->getRootAliases();
        if (count($aliases) < 1) {
            $aliases = array('t');
        }
        return $aliases[0];
    }

}
