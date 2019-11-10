@extends('master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/context_menu/dist/jquery.contextMenu.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/select.dataTables.min.css')  }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/buttons.dataTables.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/jquery.dataTables.min.css')  }}">

    <!-- select2 -->
    <link href="{{ asset('js/select2-4.0.3/dist/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .selected {
            background-color: #FFD700 !important;
        }
    </style>
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Relatório - % Capacidade Agenda <small>Filtros</small></h2>
                            <ul class="nav navbar-left panel_toolbox">
                                <li>
                                    <a class="collapse-link" id="esconder_filtros"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @include('flash::message')

                            {!! Form::open([
                            'route' => 'relCA',
                            'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('mes_de', 'Mês De', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('mes_de', $mes, $mes_de, ['class' => 'form-control select2_single']) !!}
                                </div>

                                {!! Form::label('ano_de', 'Ano De', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ano_de', $ano, $ano_de, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('mes_ate', 'Mês Até', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('mes_ate', $mes, $mes_ate, ['class' => 'form-control select2_single']) !!}
                                </div>

                                {!! Form::label('ano_ate', 'Ano Até', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ano_ate', $ano, $ano_ate, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('consultor', 'Consultor', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('consultor', $consultores_combo, $consultor, ['class' => 'form-control select2_single', 'multiple' => 'multiple', 'name'=>'consultor[]']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('filial', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('filial', $filiais, $filial, ['class' => 'form-control select2_single', 'multiple' => 'multiple', 'name'=>'filial[]']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('especialidade', 'Especialidade', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('especialidade', $especialidades, $especialidade, ['class' => 'form-control select2_single', 'multiple' => 'multiple', 'name'=>'especialidade[]']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {!! Form::submit('Filtrar', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">

                            <table id="listar" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th nowrap="nowrap">Consultor</th>
                                    <th nowrap="nowrap">Unidade</th>
                                    <th nowrap="nowrap">Especialidade</th>
                                    @foreach($cabecalho as $c)
                                        <th nowrap="nowrap">{{ $c[0] }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th colspan="3"></th>
                                    @foreach($cabecalho as $c)
                                        <th>{{ number_format($c[1], 0, ',', '.') . "%" }}</th>
                                    @endforeach
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($rel as $r)
                                    <tr>
                                        <td nowrap="nowrap">{{ $r[1] }}</td>
                                        <td nowrap="nowrap">{{ $r[2] }}</td>
                                        <td nowrap="nowrap">{{ $r[4] }}</td>
                                        @foreach($cabecalho as $c)
                                            <?php
                                                $competencia = explode('-',$c[0]);
                                                $url_parametros = '?ano='.$competencia[1].'&mes='.$competencia[0].'&consultor='.$r[0].'&filial='.$r[5].'&especialidade='.$r[6];
                                            ?>
                                            <td nowrap="nowrap"><a href="{{ route('agenda') }}<?php echo $url_parametros; ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="{{ $r[3][array_search($c[0], array_column($r[3], 0))][3] . " dias úteis, sendo " . $r[3][array_search($c[0], array_column($r[3], 0))][4] . " dia(s) com agenda. " . $r[3][array_search($c[0], array_column($r[3], 0))][6] . " agenda(s) livre(s)." }}">{{ number_format($r[3][array_search($c[0], array_column($r[3], 0))][5], 0, ',', '.') . "%" }}</a></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('scripts')


            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.select.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/jquery.dataTables.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.buttons.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/jquery.dataTables.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.bootstrap.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/bootbox.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/context_menu/dist/jquery.contextMenu.min.js') }}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/buttons.flash.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/jszip.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/pdfmake.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/vfs_fonts.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/buttons.html5.min.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/buttons.print.min.js')}}"></script>

            <!-- select2 -->
            <script src="{{ asset('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>

            <script type="text/javascript">

                $(document).ready(function(){

                    $('#listar').DataTable({
                        "scrollX": true,
                        "scrollY": "350px",
                        "scrollCollapse": true,
                        "paging":   false,
                        "info":     false,
                        "language": {
                            "url": "{{asset('js/datatables/Portuguese-Brasil.json')}}"
                        },
                        select: {
                            style: 'multi'
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ]
                    });

                    $(".select2_single").select2({
                        allowClear: false
                    });

                    $('[data-toggle="tooltip"]').tooltip();

                });

            </script>

@stop


