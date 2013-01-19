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

use Symfony\Component\HttpFoundation\Request;
use Saturno\Bridge\Table\Table as TableInterface;

interface GridRequest
{
    function format(TableInterface $table);
}
