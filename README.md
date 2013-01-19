DataTablesBundle - How to use 
==========================================

DataTablesBundle is a symfony package to create and manage (of easy way) tables using datatables jquery plugin and (mainly doctrine's) entities. 
For now, it's only project to fun in a alpha version. In the future, it will be in composer and ready to the production =) 

Index
----------------------
* [Configuring](#configuring)
* [Simple example](#simple-example)
* [Roadmap](#roadmap)
* [License](#license)



Configuring
-----------------------------

Call the javascript plugins and styles in the twig template 

```
{% javascripts
    '@SaturnoDataTablesBundle/Resources/public/js/jquery.min.js'    
    '@SaturnoDataTablesBundle/Resources/public/js/jquery.dataTables.min.js'
%}
    <script src="{{ asset_url }}"></script>
{% endjavascripts %}
{% stylesheets 'bundles/saturnodatatables/css/*' filter='cssrewrite'
    '@SaturnoDataTablesBundle/Resources/public/css/jquery.dataTables.css'
%}
    <link rel="stylesheet" href="{{ asset_url }}" />
{% endstylesheets %}
```


Simple Example
------------------------------

### Creating the DataTable Class 

At first, we need create the entity that represents your datatables. This table will contain instances of a same type.

```php
<?php    

// the simple entity (usage for doctrine, for example)  
class User
{
    // attributes, similiar on DataTable Class columns (below). 
    protected $id;
    protected $name;
    protected $date;

    public function __construct($id, $name, $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
    }

    /* 
    * IMPORTANT!!! Here, this class contains the set and get accessors, it's used in package instead of reflection 
    * to that pseudo attributes can be used on tables.
    */

}

```

```php
<?php
// this is the DataTable class
Class UserTable extends \Saturno\DataTablesBundle\Element\Table
{
    public function configure()
    {
        $this->hasColumn('id','Code')
            ->hasColumn('name','Name' )
            ->hasColumn('date','Birthday');
    }
}
```


### Coding your action 

```php
<?php 
        // into action controller  ...
        
        // Use factory service to get your DataTable 
        $factory = $this->get('saturno_datatables_factory');
        $table = $factory->getTable('AcmeDemoBundle:User');
        // create, get or search your entities 
        $user1 = new User(1,'Joseph','2013-05-23');
        $user2 = new User(2,'Hellena','1988-06-27');
        // insert the entities into datatable 
        $table->setBody(array(
            $user1,
            $user2
        ));

        // pass to table to the view, normally 
        $vars = array(
            'table' => $table,
        );
        $html = $this->renderView('SaturnoDataTablesBundle:examples:simple.html.twig', $vars);

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setContent($html);

        return $response;
```

### Magic in the view !!! 

In the view, on twig, use the object table 

```twig
    {# rendering the datable #}
    {{  table_render(table) }}

    {# using the magic javascript default #}
    {{ table_render_js(table) }}
    
```

ROADMAP
------------------------------------------------
* Work better with tests
* Write the command cli that will to create DataTables classes 
* Improve Trait used by Repositories (resolve many problems with aliases) 
* Use Travis
* Create the subpackage of filter (it's awesome and ready \o/ ) 
* Install by composer 
* Write the JqGridTable using Saturno\Bridge\Table =)



License
-----------------------------------

Saturno\DataTablesBundle Copyright (C) 2013 Claudson Oliveira

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.

