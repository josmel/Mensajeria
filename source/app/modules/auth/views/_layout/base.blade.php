<!doctype html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]> <html lang="es"> <![endif]-->
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>SMART MOBILE WAY | Soluciones Corporativas</title>
  <meta name="title" content="SMART MOBILE WAY | Soluciones Corporativas"/>
  <meta name="viewport" content="width=device-width"/>
  <meta name="language" content="es"/>
  <meta name="robots" content="noindex"/>
  <link href="{{ asset('static/img/favicon.ico') }}" rel="icon" type="image/vnd.microsoft.icon"/>
  <link href="{{ asset('static/img/favicon.ico') }}" rel="shortcut icon" type="image/x-icon"/>
  {{ HTML::style(asset('http://fonts.googleapis.com/css?family=Cuprum')) }}
  {{ HTML::style(asset('static/css/admin.css?v=10')) }}<!--[if lt IE 9]>
  {{ HTML::script(asset('static/js/html5shiv.js')) }}<![endif]-->
</head>
<body>
  <div class="content-login"><img src="{{ asset('static/img/smw.png') }}" class="login-logo"/>
      
  <!-- content starts -->
  
  @yield('content')
  
  <!-- content end -->
  
  </div>
  <footer>
    <div class="box-center">
      <p>&copy;Copyright 2014. Todos los derechos reservados. Este admin fué desarrollado por <a href="javascript:;" target="_blank">Smart Mobile Way</a></p>
    </div>
      
    @yield('footer_script')
    
  </footer>
    @if ($_request = \Helpers::requestMCA(Route::currentRouteAction())) @endif
    <script type="text/javascript">
        var yOSON ={
            "modulo":"{{ $_request['module'] }}",
            "controller":"{{ $_request['controller'] }}",
            "action":"{{ $_request['action'] }}",
            "baseHost":"{{ asset('/') }}",
            "statHost":"{{ asset('/static') }}/",
            "statVers":"?vcx27w",
            "min":"","AppCore":{},"AppSandbox":{},"AppSchema":{"modules":{},"requires":{}}
          };
    </script>
    {{ HTML::script(asset('static/js/libs/framework.js')) }}
    {{ HTML::script(asset('static/js/application/yoson.js')) }}
    {{ HTML::script(asset('static/js/modules/admin.js')) }}
    {{ HTML::script(asset('static/js/application/schemas/admin.js')) }}
    {{ HTML::script(asset('static/js/application/appLoad.js')) }}
</body>