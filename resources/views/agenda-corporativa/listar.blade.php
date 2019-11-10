@extends('master')



@section('content')

    <div class="right_col" role="main">
        <div class="">

            @include('flash::message')

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Agenda Corporativa <small>Filtros</small></h2>
                            <ul class="nav navbar-left panel_toolbox">
                                <li>
                                    <a class="collapse-link" id="esconder_filtros"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            {!! Form::open([
                                'route'  => 'agenda-corporativa.pesquisar',
                                'method' => 'POST',
                                'class'  => 'form-horizontal form-label-left',
                                'id'     => 'form_filtrar'
                            ]) !!}



                            <div class="form-group">
                                {!! Form::label('data', 'Data', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('data', $data, array('class' => 'form-control', 'id' => 'data', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('combo_semana', 'Mostrar', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('semana', $combo_semana, array(), ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('tipo_agenda', 'Tipo de Agenda', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('tipo_agenda', $tipos_agenda, null, ['class' => 'form-control select2_single', 'name'=>'tipo_agenda', 'placeholder' => 'Todos']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('consultor', 'Consultor', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('consultor', $consultores_combo, null, ['class' => 'form-control select2_single', 'multiple' => 'multiple', 'name'=>'consultor[]']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('torre', 'Torre', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('torre', $torres_combo, null, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('filial', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('filial', $filiais_combo, null, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('cliente', 'Cliente', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="cliente_pesquisada" value="">
                                    {!! Form::select('cliente', [], null, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('projeto_sel', 'Projeto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="projeto_pesquisada" value="">
                                    {!! Form::select('projeto', [], null, ['class' => 'form-control select2_single','placeholder' => 'Todos']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                    <input type="hidden" name="semana_direcao" value="">
                                    <input type="hidden" name="primeira_data_tabela" value="">

                                    {!! Form::submit('Filtrar', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Agenda Corporativa <small>Resultado</small></h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <button type="button" id="btn_anterior" class="btn btn-primary"><span class="fa fa-arrow-left"></span> Semana Anterior</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" id="btn_seguinte" class="btn btn-primary">Semana Seguinte <span class="fa fa-arrow-right"></span></button>
                                </div>
                            </div>
                            <br />
                            <div id="div_result_form"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />-->
    <link href="{{ asset('js/select2-4.0.3/dist/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-fancybox.css') }}"/>
    <style type="text/css">
        th, td { white-space: nowrap; }
        div.dataTables_wrapper {
            margin: 0 auto;
        }

        .highlight {
            background-color: #FF9;
        }

        /*
        .DTFC_ScrollWrapper {
            font-size: 9px;
        }
        */

    </style>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('datatables/js/dataTables.fixedColumns.min.js') }}"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>-->
    <script type="text/javascript" src="{{ asset('js/fancybox/jquery.fancybox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inputmask/dist/min/inputmask/inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('inputmask/dist/min/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        const route_cliente_ajax    = '{{ route('relCO.ajax.buscarCliente') }}';
        const route_projeto_ajax    = '{{ route('agenda-corporativa.ajax.buscarProjeto') }}';
        const route_filtro          = '{{ route('agenda-corporativa.pesquisar') }}';
        const url_language          = "{{asset('js/datatables/Portuguese-Brasil.json')}}";
    </script>
    <script type="text/javascript" src="{{ asset('js/paginas/agenda-corporativa/listar.js') }}"></script>
@endsection


