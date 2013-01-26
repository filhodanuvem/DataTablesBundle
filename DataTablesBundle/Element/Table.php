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

use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

/**
 * Classes that extends this represents some dataTable and can print
 * a template with that entity
 */
abstract class Table implements \Saturno\Bridge\Table\Table
{
    private $templateEngine;

    protected $template;

    protected $javascript;

    protected $columns;

    protected $body;

    protected $settings;


    public function __construct(\Twig_Environment $template)
    {
        $this->templateEngine = $template;
        $this->template   = 'SaturnoDataTablesBundle:Skeleton:table.html.twig';
        $this->javascript = 'SaturnoDataTablesBundle:Skeleton:javascript.html.twig';
        $this->body     = new Body($this);
        $this->columns  = array();
        $this->settings = array(
            'name' => $this->getName(),
            'columns' => &$this->columns,
            'body' => &$this->body,
            'config'  => array(

            )
        );
        $this->build();
    }

    /**
     * @param string $property access until some property
     * @param string $label title the column in template
     * @param Array $settings extras
     * @return Table
     */
    public function hasColumn($property, $label, Array $settings = array())
    {
        $column = new Column($property, $label, $settings);
        $this->columns[$property] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        if (!is_string($template)) {
            throw new \InvalidArgumentException('Expected a template string name');
        }
        $this->template = $template;

        return $this;
    }

    /**
     * @param array $content
     * @return Table
     */
    public function setBody(Array $content)
    {
        foreach ($content as $entity) {
            $this->body->addRow($entity);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body->getRows();
    }

    /**
     * @param mixed $index
     * @return string
     * @throws \OutOfBoundsException
     * @throws \InvalidArgumentException
     */
    public function getColumnName($index)
    {
        if (!is_numeric($index)) {
            throw new \InvalidArgumentException('Expected a valid index');
        }

        if ($index >= count($this->columns)) {
            $total = count($this->columns);
            throw new \OutOfBoundsException("Trying access column {$index} of {$total} ");
        }
        $values = array_values($this->columns);

        return $values[$index]->getName();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        $reflect = new \ReflectionClass($this);
        return str_replace('Table','',$reflect->getShortName());
    }

    /**
     * @return array
     */
    private function getSettings()
    {
        $this->settings['config'] = array_merge($this->settings['config'], $this->getDefaultOptions());
        return $this->settings;
    }

    /**
     * @return string
     */
    public function createView()
    {
        $vars = $this->getSettings();
        return $this->templateEngine->render($this->template, $vars);
    }

    /**
     * @return string
     */
    public function createJavascript()
    {
        $vars = $this->getSettings();
        return $this->templateEngine->render($this->javascript, $vars);
    }


}
