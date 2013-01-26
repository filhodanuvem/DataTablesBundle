Getting start with DataTablesBundle
===========================================

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

Usage guide
-----------------------------------
* [Basic usage](./DataTablesBundle/Resources/doc/1-basic_usage.md)
* [Simple example](./DataTablesBundle/Resources/doc/2-entity_with_aggregate.md)
* [Roadmap](./DataTablesBundle/Resources/doc/3-using_ajax.md)
