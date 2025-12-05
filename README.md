my_project_name
===============

A Symfony project created on June 24, 2018, 3:03 pm.


COSAS QUE HACER 

ENTIDAD           ACCION
TipoDocumento     Asignar en el campo visible "true"
TipoTramite		  Asignar en el Campo letra una IN o INT o LIC segun el tramite


Como manejar variables en twig

For $_POST variables use this :
{{ app.request.request.add(['var1', 'data1']) }}
{{ app.request.request.get(0) }}
{{ app.request.request.get(1) }}

For $_GET variables use this :
{{ app.request.query.add(['var2', 'data2']) }}
{{ app.request.query.get(0) }}
{{ app.request.query.get(1) }}

For $_COOKIE variables use this :
{{ app.request.cookies.add(['var3' , 'data3']) }}
{{ app.request.cookies.get(0) }}
{{ app.request.cookies.get(1) }}

For $_SESSION variables use this :
{{ app.session.set('var4', 'data4') }}
{{ app.session.get('var4') }} <!-- shows 'data4 -->
Or
{{ app.request.session.set('var4', 'data4') }}
{{ app.request.session.get('var4') }} # shows 'data4