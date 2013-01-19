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

interface Table
{

    public function createView();

    public function createJavascript();

    public function hasColumn($property, $label, Array $settings = array());

    public function getColumns();

    public function setBody(Array $content);

    public function getBody();

    public function getColumnName($index);
}
