<?php
/**
 *
 * @author Claudson Oliveira <claudsonweb@gmail.com> 1/3/13
 */

namespace Saturno\DataTablesBundle\Manager;

use Saturno\DataTablesBundle\Exceptions\TableNotFoundException;

class TableFactory
{
    protected  $templateEngine;

    protected $kernel;

    public function __construct(\Twig_Environment $template,\Symfony\Component\HttpKernel\Kernel $kernel)
    {
        $this->templateEngine = $template;
        $this->kernel = $kernel;
    }

    public function getTable($table)
    {
        $tableClass = $this->getTableClassName($table);

        if (!class_exists($tableClass)) {
            throw new TableNotFoundException("Class {$tableClass} not found ");
        }

        return new $tableClass($this->templateEngine);
    }


    private function getTableClassName($identifier)
    {
        if (strstr($identifier, ':') === false) {
            throw new \InvalidArgumentException('Identifiers needs to be the form PathToBundle:NameWithoutTable');
        }

        list($bundle,$table) = explode(':',$identifier);
        $table .= 'Table';

        $bundles = $this->kernel->getBundles();
        if (!array_key_exists($bundle, $bundles)) {
            throw new \UnexpectedValueException("Bundle {$bundle} does not exist");
        }

        $selectedBundle =  $bundles[$bundle];
        if (is_object($selectedBundle)) {
            $selectedBundle = get_class($selectedBundle);
        }

        $selectedBundle =  mb_substr($selectedBundle,0, strrpos($selectedBundle, '\\'));

        return $selectedBundle.'\\DataTable\\'.$table;

    }
}
