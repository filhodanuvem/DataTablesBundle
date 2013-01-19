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

trait Repostiory
{
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

    protected  function setOrderBy(QueryBuilder &$qb, Request $request)
    {
        try{
            $field = $request->get('orderBy');

            if (strstr('.', $field) === false) {
                $access = $this->getAlias($qb).'.'.$field;
            }

            if ($qb->getDQLPart('OrderBy')) {
                $qb->addOrderBy($access, $request->get('orderDir'));

                return $this;
            }
            $qb->orderBy($access, $request->get('orderDir'));

        } catch (\UnexpectedValueException $e) {

        }

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
