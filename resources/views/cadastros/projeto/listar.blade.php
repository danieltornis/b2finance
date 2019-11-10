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
                            <h2>Cadastros -> Projeto <a href="{{ route('ambiente') }}" type="button" class="btn btn-default"><i class="fa fa-exchange"></i> {{ Session::get('ambiente') }}</a> <small>Filtros</small></h2>
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
                            'route' => 'projeto',
                            'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('cliente', 'Cliente', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="cliente_pesquisada" value="{{ $cliente }}">
                                    {!! Form::select('cliente', $clientes, $cliente, ['class' => 'form-control select2_single']) !!}
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
                                {!! Form::label('coordenador', 'Coordenador', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('coordenador', $coordenadores_combo, $coordenador, ['class' => 'form-control select2_single', 'multiple' => 'multiple', 'name'=>'coordenador[]']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('ativo', 'Status', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ativo', $status_combo, $status, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('tipo', 'Tipo', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('tipo', $tipo_combo, $tipo, ['class' => 'form-control select2_single']) !!}
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
                                    <th nowrap="nowrap">Código</th>
                                    <th nowrap="nowrap">Cliente</th>
                                    <th nowrap="nowrap">Projeto</th>
                                    <th nowrap="nowrap">Coordenador</th>
                                    <th nowrap="nowrap">Status</th>
                                    <th nowrap="nowrap">Tipo</th>
                                    <th style="max-width: 40px">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rel as $r)
                                    <tr>
                                        <td nowrap="nowrap">{{ $r->equip_id }}</td>
                                        <td nowrap="nowrap">{{ $r->cliente }}</td>
                                        <td nowrap="nowrap">{{ trim($r->cod_projeto) . ' - ' . $r->nome_projeto }}</td>
                                        <td nowrap="nowrap">{{ $r->coordenador }}</td>
                                        <td nowrap="nowrap">{{ $r->status }}</td>
                                        <td nowrap="nowrap">{{ $r->tipo }}</td>
                                        <td>
                                            @can('acesso_projeto')
                                                <a href="{{ route('projeto.acesso', $r->equip_id) }}" class="btn btn-primary btn-xs">Acesso</a>
                                            @else
                                                <a href="#" class="btn btn-default btn-xs disabled">Acesso</a>
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

                    $('select[name=cliente]').on('change', function(){
                        var cliente_id = this.value;
                        $.ajax({
                            method: "POST",
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: "{{ route('projeto.ajax.buscarProjeto') }}",
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
                                                    .attr("value", key.equip_id).text(key.equip_nome + ' - ' + key.equip_desc));
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


