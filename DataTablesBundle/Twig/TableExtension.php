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

namespace Saturno\DataTablesBundle\Twig;

use \Saturno\Bridge\Table\Table;

class TableExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'table_render';
    }

    public function getFunctions()
    {
        return array(
            'table_render' => new \Twig_Function_Method($this, 'table_render', array('is_safe' => array('html'), 'pre_escape' => 'html')),
            'table_render_js' => new \Twig_Function_Method($this, 'table_render_js', array('is_safe' => array('html'), 'pre_escape' => 'html')),
        );
    }

    public function table_render(Table $table)
    {
        return $table->createView();
    }

    public function table_render_js(Table $table)
    {
        return $table->createJavascript();
    }



}
