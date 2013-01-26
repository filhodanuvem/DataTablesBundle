1 - Basic usage
==========================

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
        // create, get or search your entities, probably you use repositories for it 
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

