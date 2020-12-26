<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
    <link href="/css/default.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/css/fonts.css" rel="stylesheet" type="text/css" media="all" />


    @yield('head')


    <!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->

</head>
<body>
<div id="app">

</div>
<div id="header-wrapper">
    <div id="header" class="container">
        <div id="logo">
            <h1><a href="/">Klipe_LD projects</a></h1>
        </div>
        <div id="menu">
            <ul>
                <li class="{{Request::path() === '/' ? 'current_page_item' : ''}}"><a href="/" accesskey="1" title="">Главная страница</a></li>
            </ul>
        </div>
    </div>

</div>
@yield('content')

<div id="copyright" class="container">
    <p>&copy; Klipe_LD production | <a href="https://klipeld.github.io/" rel="nofollow">Projects</a>.</p>
</div>
</body>
</html>