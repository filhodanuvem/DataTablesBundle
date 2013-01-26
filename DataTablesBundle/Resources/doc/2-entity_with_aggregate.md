2 - Entity with a aggregate
============================================

Probably, your entity contains other entity, to represent this case in a DataTable, we use the notation . (dot) to access other object.
This is so easy.

```php
<?php

Class ProductTable extends \Saturno\DataTablesBundle\Element\Table
{
    public function build()
    {
        $this->hasColumn('id','Code')
            ->hasColumn('name','Name' )
            ->hasColumn('user.name','User'); 
            //here, we see that Product entity contains a User entity, and this table will show User's name 
    }
     
     // ...others methdos
}


```