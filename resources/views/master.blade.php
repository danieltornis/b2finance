<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link rel="shortcut icon" href="favicon.ico" />
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>b2finance</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    <!-- Bootstrap core CSS -->

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('fonts/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!--
    <link href="{ { asset('css/icheck/flat/green.css') }}" rel="stylesheet">
    -->

    @yield('styles')


</head>


<body class="nav-md">

<div class="container body">


    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ route('index') }}" class="site_title">
                        <img src="{{ asset('images/logo.jpg') }}" height="48" border="0">
                        <span>b2finance</span>
                    </a>
                </div>
                <div class="clearfix"></div>

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <!--<h3>General</h3>-->
                        <ul class="nav side-menu">

                            <li style="display: none"><a><i class="fa fa-user"></i> Usuário <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('perfil_visualizar')
                                    <li><a href="{{ route('perfil') }}">Perfil</a></li>
                                    @endcan

                                    @can('acesso_visualizar')
                                    <li><a href="{{ route('acesso') }}">Acesso</a></li>
                                    @endcan

                                    @can('usuario_perfil_visualizar')
                                    <li><a href="{{ route('perfilusuario') }}">Usuário vs Perfil</a></li>
                                    @endcan
                                </ul>
                            </li>

                            <li style="display: none"><a><i class="fa fa-file-o"></i> Cadastros <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('projeto_visualizar')
                                    <li><a href="{{ route('projeto') }}">Projeto</a></li>
                                    @endcan

                                    @can('tabela_padrao_visualizar')
                                    <li><a href="{{ route('tabela_padrao') }}">Tabela Padrão</a></li>
                                    @endcan

                                    @can('produto_visualizar')
                                    <li><a href="{{ route('produto') }}">Liberar Produto</a></li>
                                    @endcan

                                    @can('link_visualizar')
                                    <li><a href="{{ route('link') }}">Link</a></li>
                                    @endcan
                                </ul>
                            </li>

                            <li style="display: none"><a><i class="fa fa-line-chart"></i> Relatório <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    @can('rel_capacidade_agenda')
                                        <li><a href="{{ route("relCA")  }}">% Capacidade Agenda</a></li>
                                    @endcan

                                    @can('rel_OS')
                                        <li><a href="{{ route("relCO")  }}">OS</a></li>
                                    @endcan

                                    @can('rel_intercompany')
                                    <li><a href="{{ route("relIntercompany")  }}">Intercompany</a></li>
                                    @endcan

                                    @can('rel_Geral')
                                    <li><a href="{{ route("relGeral")  }}">Geral</a></li>
                                    @endcan
                                </ul>
                            </li>

                            <li><a href="{{ route("agenda")  }}"><i class="fa fa-calendar"></i> Agenda</a></li>

                            @can('agenda_visualizar')
                                <li><a href="{{ route("agenda-corporativa")  }}"><i class="fa fa-calendar-o"></i> Agenda Corporativa</a></li>
                            @endcan
                        </ul>
                    </div>


                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons --
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-left"></ul>

                    <div class="pull-right">
                        <ul class="nav navbar-nav">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('images/user.png') }}" alt="">{{ Auth::user()->name  }} <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                    <li>
                                        <a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Sair</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <!-- menu cima -->
                </nav>
            </div>

        </div>

        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')



    </div>
    <!-- /page content -->
</div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>

<!--
Scripts
-->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<!-- bootstrap progress js
<script src="{ { asset('js/progressbar/bootstrap-progressbar.min.js') }}"></script>
-->
<script src="{{ asset('js/nicescroll/jquery.nicescroll.min.js') }}"></script>


<!-- icheck
<script src="{ { asset('js/icheck/icheck.min.js') }}"></script>
-->
<script src="{{ asset('js/custom.js') }}"></script>


<script type="text/javascript">
    $(document).ready(function(){

        //$('#menu_toggle').trigger('click');
        $('#flash-overlay-modal').modal();

        $('.child_menu').each(function(){
            var count = $(this).find('li').length;
            if(count > 0) {
                $(this).parent('li').eq(0).css('display','block');
            }
        });
    })
</script>

@yield('scripts')

</body>

</html>