@extends('master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/context_menu/dist/jquery.contextMenu.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/select.dataTables.min.css')  }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/buttons.dataTables.min.css')  }}">

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Relatório Geral <small>Filtros</small></h2>
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
                            'route' => 'relGeral',
                            'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('datade', 'Data De ', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('datade', $datade, array('class' => 'form-control', 'id' => 'datade', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('dataate', 'Data Até ', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('dataate', $dataate, array('class' => 'form-control', 'id' => 'dataate', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
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
                                    <th nowrap="nowrap">Data OS</th>
                                    <th nowrap="nowrap">Filial OS</th>
                                    <th nowrap="nowrap">Consultor</th>
                                    <th nowrap="nowrap">Login</th>
                                    <th nowrap="nowrap">E-mail Consultor</th>
                                    <th nowrap="nowrap">Status Consultor</th>
                                    <th nowrap="nowrap">Filial Mestre</th>
                                    <th nowrap="nowrap">Especialidade</th>
                                    <th nowrap="nowrap">Custo Consultor</th>
                                    <th nowrap="nowrap">Valor Hora Consultor</th>
                                    <th nowrap="nowrap">Torre</th>
                                    <th nowrap="nowrap">Regime</th>
                                    <th nowrap="nowrap">Cliente</th>
                                    <th nowrap="nowrap">Cidade</th>
                                    <th nowrap="nowrap">Estado</th>
                                    <th nowrap="nowrap">CNPJ</th>
                                    <th nowrap="nowrap">E-mail Cliente</th>
                                    <th nowrap="nowrap">Translado Cliente</th>
                                    <th nowrap="nowrap">Pedágio Cliente</th>
                                    <th nowrap="nowrap">Km</th>
                                    <th nowrap="nowrap">Fator Km</th>
                                    <th nowrap="nowrap">Fator Imposto</th>
                                    <th nowrap="nowrap">Valor Refeição</th>
                                    <th nowrap="nowrap">Cliente Interno?</th>
                                    <th nowrap="nowrap">Gerente</th>
                                    <th nowrap="nowrap">Executivo de Vendas</th>
                                    <th nowrap="nowrap">Código Projeto</th>
                                    <th nowrap="nowrap">Nome do Projeto</th>
                                    <th nowrap="nowrap">Status Projeto</th>
                                    <th nowrap="nowrap">Tipo Hora</th>
                                    <th nowrap="nowrap">Tipo Hora Valor</th>
                                    <th nowrap="nowrap">RD?</th>
                                    <th nowrap="nowrap">Tipo Projeto</th>
                                    <th nowrap="nowrap">Hora Analista</th>
                                    <th nowrap="nowrap">Hora Coordenação</th>
                                    <th nowrap="nowrap">Coordenador</th>
                                    <th nowrap="nowrap">Reembolso Refeição?</th>
                                    <th nowrap="nowrap">Valor Refeição</th>
                                    <th nowrap="nowrap">Service Desk?</th>
                                    <th nowrap="nowrap">Hora Início</th>
                                    <th nowrap="nowrap">Hora Fim</th>
                                    <th nowrap="nowrap">Hora Inicio Almoço</th>
                                    <th nowrap="nowrap">Hora Fim Almoço</th>
                                    <th nowrap="nowrap">Horas Trabalhadas</th>
                                    <th nowrap="nowrap">Horas Almoço</th>
                                    <th nowrap="nowrap">Horas Total</th>
                                    <th nowrap="nowrap">OS Com Traslado?</th>
                                    <th nowrap="nowrap">Os Faturada?</th>
                                    <th nowrap="nowrap">Status OS</th>
                                    <th nowrap="nowrap">Reembolso?</th>
                                    <th nowrap="nowrap">Home Office?</th>
                                    <th nowrap="nowrap">Valor Hora OS</th>
                                    <th nowrap="nowrap">Valor Refeição OS</th>
                                    <th nowrap="nowrap">Valor Pedágio OS</th>
                                    <th nowrap="nowrap">Quantidade Km</th>
                                    <th nowrap="nowrap">Valor KM</th>
                                    <th nowrap="nowrap">Data Execução do Trabalho</th>
                                    <th nowrap="nowrap">Data da Inclusão da OS</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rel as $r)
                                    <tr>
                                        <td nowrap="nowrap">{{ $r->id_os }}</td>
                                        <td nowrap="nowrap">{{ date('d/m/Y' , strtotime($r->data)) }}</td>
                                        <td nowrap="nowrap">{{ $r->filial }}</td>
                                        <td nowrap="nowrap">{{ $r->consultor }}</td>
                                        <td nowrap="nowrap">{{ $r->login }}</td>
                                        <td nowrap="nowrap">{{ $r->email }}</td>
                                        <td nowrap="nowrap">@if($r->status_usuario=='0') {{ 'Ativo' }} @else {{ 'Bloqueado' }} @endIf</td>
                                        <td nowrap="nowrap">{{ $r->filialmestre }}</td>
                                        <td nowrap="nowrap">{{ $r->especialidade }}</td>
                                        <td nowrap="nowrap">{{ $r->custo_colaborador }}</td>
                                        <td nowrap="nowrap">{{ $r->valor_hora }}</td>
                                        <td nowrap="nowrap">{{ $r->torre }}</td>
                                        <td nowrap="nowrap">@if($r->regime=='0') {{ 'CLT' }} @else {{ 'PJ' }} @elseIf</td>
                                        <td nowrap="nowrap">{{ $r->cliente }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_cidade }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_estado }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_cpf_cnpj }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_email }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_translado }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_pedagio }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_km }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_fatorkm }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_fator_imposto }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_valor_refeicao }}</td>
                                        <td nowrap="nowrap">{{ $r->clt_cliente_interno }}</td>
                                        <td nowrap="nowrap">{{ $r->gerente }}</td>
                                        <td nowrap="nowrap">{{ $r->executivov }}</td>
                                        <td nowrap="nowrap">{{ $r->cod_projeto }}</td>
                                        <td nowrap="nowrap">{{ $r->nome_projeto }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_ativo }}</td>
                                        <td nowrap="nowrap">{{ $r->tipoh_nome }}</td>
                                        <td nowrap="nowrap">{{ $r->tipoh_valor }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_rd }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_tipo }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_h_analista }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_h_coordenador }}</td>
                                        <td nowrap="nowrap">{{ $r->coordenador }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_reembolso_refeic }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_valor_refeicao }}</td>
                                        <td nowrap="nowrap">{{ $r->equip_service_desk }}</td>
                                        <td nowrap="nowrap">{{ $r->os_hinicio }}</td>
                                        <td nowrap="nowrap">{{ $r->os_hfim }}</td>
                                        <td nowrap="nowrap">{{ $r->os_almoinicio }}</td>
                                        <td nowrap="nowrap">{{ $r->os_almofim }}</td>
                                        <td nowrap="nowrap">{{ $r->os_htrab }}</td>
                                        <td nowrap="nowrap">{{ $r->os_halmo }}</td>
                                        <td nowrap="nowrap">{{ $r->os_total1 }}</td>
                                        <td nowrap="nowrap">{{ $r->os_com_traslado }}</td>
                                        <td nowrap="nowrap">{{ $r->os_faturada }}</td>
                                        <td nowrap="nowrap">{{ $r->os_status }}</td>
                                        <td nowrap="nowrap">{{ $r->os_reembolso }}</td>
                                        <td nowrap="nowrap">{{ $r->os_home_office }}</td>
                                        <td nowrap="nowrap">{{ $r->os_valor_hora }}</td>
                                        <td nowrap="nowrap">{{ $r->os_valor_refeicao }}</td>
                                        <td nowrap="nowrap">{{ $r->os_valor_pedagio }}</td>
                                        <td nowrap="nowrap">{{ $r->os_qtd_km }}</td>
                                        <td nowrap="nowrap">{{ $r->os_valor_km }}</td>
                                        <td nowrap="nowrap">{{ date('d/m/Y' , strtotime($r->os_data_exec_trab)) }}</td>
                                        <td nowrap="nowrap">{{ date('d/m/Y' , strtotime($r->os_data_inclusao)) }}</td>
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

                    $("#datade").datepicker(); // Sample
                    $("#dataate").datepicker(); // Sample

                });

            </script>

@stop


