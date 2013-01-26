3 - Using Ajax
==========================================

Maybe, you have many data in a table of database and you can't get all in one query. In DataTables we resolve this problem using ajax, when the each request, we have any entities, instead of all. 
This example uses doctrine orm, **so, that entity User contains annotations right now!** 

Configuring the DataTable 
----------------------------

```php 
<?php 
// ... into the DataTable Class

public function getDefaultOptions()
{
    return array(
        'url' => 'acme_example_datatables_ajax' // url is the target route 
	);
}

```
In controller, we need two actions, one that show the table normally (but without the body, obviously) and other that will return the content to DataTable. See bellow: 


Im action 
---------------------

```php
<?php

Class UserController 
{
    /**
    * @Route("/ajax")
    */
    public function ajaxAction()
    {
        $factory = $this->get('saturno_datatables_factory');
        $table = $factory->getTable('AcmeExampleDataTablesBundle:UserAjax');

        $vars = array(
            'table' => $table,
        );
        $html = $this->renderView('SaturnoDataTablesBundle:examples:simple.html.twig', $vars);

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setContent($html);
        // show the table normally 
        return $response;
    }
    
    /**
    * see that this is a target of DataTable
    * @Route("/request", name="acme_example_dataTables_ajax");
    */
    public function requestAction()
    {

        // get table
        $factory = $this->get('saturno_datatables_factory');
        $table = $factory->getTable('AcmeExampleDataTablesBundle:UserAjax');

        // use table into request
        $request = $this->get('saturno_table_request');
        $request->format($table);

        // call the repository method
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('AcmeExampleDataTablesBundle:User');
        $users = $repo->getAll($request);

        // pass the data to the table and insert this table on response
        $table->setBody($users);
        $response = new \Saturno\DataTablesBundle\HTTP\Response($table);
        return $response;
    }

}

```

Trait Repository
---------------------------------------

Notice that requestAction we use a Respositoy normally to catch users from database. the method getAll these repository receives a Request object, because interactions of user in browser change our query (for example, on clicking in a header, we need reorder the data =).
DataTablesBundle contains a Trait Repository that provide many methods that resolve this problems of ordering, filtering, set limit and etc.

```php
<?php
class UserRepository extends EntityRepository
{
	use \Saturno\DataTablesBundle\Traits\Repository; // insert trait

	public function getAll(Request $request)
	{
		$qb = $this->createQueryBuilder('t');
		// filter method make all, it's magic! =) 
		$this->filter($qb, $request);
		return $qb->getQuery()->getResult();
	}
}

```