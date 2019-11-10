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
                            <h2>Liberação de Produto <small>Filtros</small></h2>
                            <ul class="nav navbar-left panel_toolbox">
                                <li>
                                    <a class="collapse-link" id="esconder_filtros"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>

                            <div class="pull-right">
                                <!-- Split button -->

                                <div class="btn-group">
                                    @can('produto_liberar')
                                        <a href="{{ route('produto.liberar') }}" type="button" class="btn btn-primary">Nova Liberação</a>
                                    @else
                                        <a href="#" type="button" class="btn btn-default btn-xs disabled">Nova Liberação</a>
                                    @endcan
                                </div>

                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @include('flash::message')

                            {!! Form::open([
                                'route' => 'produto',
                                'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('filial', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('filial', $filial_combo, $filial, ['class' => 'form-control select2_single']) !!}
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
                                {!! Form::label('produto', 'Produto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('produto', $produto_combo, $produto, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('ativo', 'Status', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ativo', $ativo_combo, $ativo, ['class' => 'form-control select2_single']) !!}
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
                                    <th nowrap="nowrap">Status</th>
                                    <th nowrap="nowrap">Filial</th>
                                    <th nowrap="nowrap">Cliente</th>
                                    <th nowrap="nowrap">CNPJ</th>
                                    <th nowrap="nowrap">Produto</th>
                                    <th nowrap="nowrap">Data Corte</th>
                                    <th style="max-width: 40px">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rel as $r)
                                    <tr>
                                        <td nowrap="nowrap">@if($r->PROD_ATIVO=='A') ATIVO @else INATIVO @endif</td>
                                        <td nowrap="nowrap">{{ $r->filial_descricao }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_nome_razao }}</td>
                                        <td nowrap="nowrap">{{ $r->PROD_CNPJ }}</td>
                                        <td nowrap="nowrap">{{ $r->TP_DESCRICAO }}</td>
                                        @if($r->PROD_DT_CORTE != '')
                                            <td nowrap="nowrap">{{ date('d/m/Y' , strtotime($r->PROD_DT_CORTE)) }}</td>
                                        @else
                                            <td nowrap="nowrap"></td>
                                        @endif
                                        <td>
                                            @can('produto_editar')
                                                <a href="{{ route('produto.editar', $r->PROD_ID) }}" class="btn btn-info btn-xs">Editar</a>
                                                <a id="excluir-{{ $r->PROD_ID }}" class="btn btn-danger btn-xs">Excluir</a>
                                                {!! Form::model($r, array('route' => array('produto.excluir', $r->PROD_ID), 'id' => 'form-'.$r->PROD_ID)) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::submit('Excluir', array('class' => 'btn btn-danger btn-xs','style' => 'display:none' )) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <a href="#" class="btn btn-default btn-xs disabled">Editar</a>
                                                <a id="excluir-{{ $r->PROD_ID }}" class="btn btn-default btn-xs disabled">Excluir</a>
                                            @endcan
                                        </td>
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

                    $('select[name=filial]').on('change', function(){
                        var filial_id = this.value;
                        $.ajax({
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('produto.ajax.buscarCliente') }}",
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

                    $(document).on("click", "a[id^='excluir-']", function(e) {
                        var excluir = e.target.id.split('-')
                        var id = excluir[1]

                        bootbox.dialog({
                            message: "Tem certeza que deseja excluir o registro?",
                            title: "Confirmação de Exclusão",
                            buttons: {
                                success: {
                                    label: "Ok",
                                    className: "btn-success",
                                    callback: function() {
                                        $('#form-'+id).submit();
                                    }
                                },
                                danger: {
                                    label: "Cancelar",
                                    className: "btn-primary"
                                }
                            }
                        });
                    });
                });

            </script>

@stop


