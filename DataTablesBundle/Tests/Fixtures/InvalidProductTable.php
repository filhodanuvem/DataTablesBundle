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
namespace Saturno\DataTablesBundle\Tests\Fixtures;

class InvalidProductTable extends \Saturno\DataTablesBundle\Element\Table
{
    public function configure()
    {
        $this->hasColumn('id','Code')
            ->hasColumn('name','Name' )
            ->hasColumn('user.filho','User');
    }
}
