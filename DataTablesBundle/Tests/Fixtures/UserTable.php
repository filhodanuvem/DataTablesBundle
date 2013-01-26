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

Class UserTable extends \Saturno\DataTablesBundle\Element\Table
{
    public function build()
    {
        $this->hasColumn('id','Code')
            ->hasColumn('name','Name' )
            ->hasColumn('date','Birthday');
    }

    public function getDefaultOptions()
    {
    	return array(
            
    	);
    }

    public function getName()
    {
    	return 'datatables_user';
    }
}
