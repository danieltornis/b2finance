@extends('master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/context_menu/dist/jquery.contextMenu.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/select.dataTables.min.css')  }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/buttons.dataTables.min.css')  }}">

    <!-- select2 -->
    <link href="{{ asset('js/select2-4.0.3/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Relatório - Consulta OS <small>Filtros</small></h2>
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
                            'route' => 'relCO',
                            'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('os', 'Número OS', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::Text('os', $os, ['class' => 'form-control']) !!}
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
                                    {!! Form::select('filial', $filiais_combo, $filial, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('cliente', 'Cliente', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="cliente_pesquisada" value="{{ $cliente }}">
                                    {!! Form::select('cliente', [], $cliente, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('projeto_sel', 'Projeto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="projeto_pesquisada" value="{{ Session::get('projeto') }}">
                                    {!! Form::select('projeto_sel', [], Session::get('projeto'), ['class' => 'form-control select2_single','placeholder' => 'Todos']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('texto', 'Texto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::Text('texto', $texto, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('datade', 'Data De ', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('datade', $datade, array('class' => 'form-control', 'id' => 'datade', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                                {!! Form::label('dataate', 'Data Até ', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('dataate', $dataate, array('class' => 'form-control', 'id' => 'dataate', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('faturada', 'Faturada?', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('faturada', $faturada_combo, $faturada, ['class' => 'form-control select2_single']) !!}
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
                                    <th nowrap="nowrap">OS</th>
                                    <th nowrap="nowrap">Data</th>
                                    <th nowrap="nowrap">Consultor</th>
                                    <th nowrap="nowrap">Filial</th>
                                    <th nowrap="nowrap">Cliente</th>
                                    <th nowrap="nowrap">Projeto</th>
                                    <th nowrap="nowrap">Atividade</th>
                                    <th nowrap="nowrap">Texto</th>
                                    <th nowrap="nowrap">Hora Início</th>
                                    <th nowrap="nowrap">Hora Inicio Almoço</th>
                                    <th nowrap="nowrap">Hora Fim Almoço</th>
                                    <th nowrap="nowrap">Hora Fim</th>
                                    <th nowrap="nowrap">Horas Trabalhadas</th>
                                    <th nowrap="nowrap">Hora Traslado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rel as $r)
                                    <tr>
                                        <td nowrap="nowrap"><a href="{{ "http://187.191.96.187/projetos/os_pdf.php?id=" . $r->id_os }}" target="_blank">{{ $r->id_os }} </a></td>
                                        <td nowrap="nowrap">{{ date('d/m/Y' , strtotime($r->data)) }}</td>
                                        <td nowrap="nowrap">{{ $r->consultor }}</td>
                                        <td nowrap="nowrap">{{ $r->filial }}</td>
                                        <td nowrap="nowrap">{{ $r->cliente }}</td>
                                        <td nowrap="nowrap">{{ '(' . trim($r->cod_projeto) . ') - ' . $r->nome_projeto }}</td>
                                        <td nowrap="nowrap">{{ $r->atividade }}</td>
                                        <td nowrap="nowrap">{{ $r->texto }}</td>
                                        <td nowrap="nowrap">{{ $r->os_hinicio }}</td>
                                        <td nowrap="nowrap">{{ $r->os_almoinicio }}</td>
                                        <td nowrap="nowrap">{{ $r->os_almofim }}</td>
                                        <td nowrap="nowrap">{{ $r->os_hfim }}</td>
                                        <td nowrap="nowrap">{{ $r->horas_atividade }}</td>
                                        <td nowrap="nowrap">@if($r->os_com_traslado=='S') {{ $r->clt_translado }} @endif</td>
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

            <script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap-datepicker.js') }}"></script>

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
                            'excel'
                        ]
                    });

                    $(".select2_single").select2({
                        allowClear: false
                    });

                    $("#datade").datepicker(); // Sample
                    $("#dataate").datepicker(); // Sample

                    $('select[name=filial]').on('change', function(){
                        var filial_id = this.value;
                        $.ajax({
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('relCO.ajax.buscarCliente') }}",
                            dataType: 'json',
                            data: {filial_id : filial_id}
                        })
                                .done(function(data) {
                                    //$("select[name='cliente']").select2("destroy");
                                    var $el = $("select[name='cliente']");
                                    $el.empty(); // remove old options
                                    $el.append($("<option value='' selected='selected'>Todos</option>"));

                                    if (data != '') {
                                        $.each(data, function (value, key) {
                                            $el.append($("<option></option>")
                                                    .attr("value", key.clt_id).text(key.clt_nome_razao));
                                        });
                                        var filial_pesquisada = $('input[name=cliente_pesquisada]').val();
                                        if(filial_pesquisada > 0) {
                                            $("select[name='cliente']").val(filial_pesquisada);
                                            //$('input[name=cliente_pesquisada]').val('');
                                        }
                                    }
                                    //$("select[name='cliente']").select2();
                                    $('#cliente').trigger('change');
                                })
                                .error(function(e){
                                    //alert('Ocorreu um erro ao tentar buscar o cliente!');
                                });
                    }).trigger('change');

                    $('select[name=cliente]').on('change', function(){
                        var cliente_id = this.value;
                        $.ajax({
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('relCO.ajax.buscarProjeto') }}",
                            dataType: 'json',
                            data: {cliente_id : cliente_id}
                        })
                                .done(function(data) {
                                    //$("select[name='projeto']").select2("destroy");
                                    var $el = $("select[name='projeto_sel']");
                                    $el.empty(); // remove old options
                                    $el.append($("<option value='' selected='selected'>Todos</option>"));

                                    if (data != '') {
                                        $.each(data, function (value, key) {
                                            $el.append($("<option></option>")
                                                    .attr("value", key.equip_id).text(key.equip_desc));
                                        });
                                        var cliente_pesquisada = $('input[name=projeto_pesquisada]').val();
                                        if(cliente_pesquisada > 0) {
                                            $("select[name='projeto_sel']").val(cliente_pesquisada);
                                            //$('input[name=projeto_pesquisada]').val('');
                                        }
                                    }
                                    //$("select[name='projeto']").select2();
                                })
                                .error(function(e){
                                    //alert('Ocorreu um erro ao tentar buscar o projeto!');
                                });
                    }).trigger('change');

                });

            </script>

@stop


