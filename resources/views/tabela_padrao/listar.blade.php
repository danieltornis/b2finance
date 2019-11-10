@extends('master')

@section('styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Tabela Padrão</h2>

                            <div class="pull-right">
                                <!-- Split button -->

                                <div class="btn-group">
                                    @can('tabela_padrao_cadastrar')
                                        <a href="{{ route('tabela_padrao.cadastrar') }}" type="button" class="btn btn-primary">Nova Tabela Padrão</a>
                                    @else
                                        <a href="{{ route('tabela_padrao.cadastrar') }}" type="button" class="btn btn-default btn-xs disabled">Nova Tabela Padrão</a>
                                    @endcan
                                </div>

                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @include('flash::message')

                            <table id="listar" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Tabela</th>
                                    <th>Chave</th>
                                    <th>Descrição</th>
                                    <th style="max-width: 40px">Ação</th>
                                    <th style="max-width: 40px">Excluir</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tabelas_padrao as $tabela_padrao)
                                    <tr>
                                        <td>{{ $tabela_padrao->TP_TABELA }}</td>
                                        <td>{{ $tabela_padrao->TP_CHAVE }}</td>
                                        <td>{{ $tabela_padrao->TP_DESCRICAO }}</td>
                                        <td>
                                            @can('tabela_padrao_editar')
                                                <a href="{{ route('tabela_padrao.editar', $tabela_padrao->TP_ID) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i>Editar</a>
                                            @else
                                                <a href="{{ route('tabela_padrao.editar', $tabela_padrao->TP_ID) }}" class="btn btn-default btn-xs disabled"><i class="fa fa-pencil"></i>Editar</a>
                                            @endcan
                                        </td>
                                        <td>
                                            @can('tabela_padrao_excluir')
                                                <a id="excluir-{{ $tabela_padrao->TP_ID }}" class="btn btn-danger btn-xs">Excluir</a>
                                                {!! Form::model($tabela_padrao, array('route' => array('tabela_padrao.excluir', $tabela_padrao->TP_ID), 'id' => 'form-'.$tabela_padrao->TP_ID)) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::submit('Excluir', array('class' => 'btn btn-danger btn-xs','style' => 'display:none' )) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <a id="excluir-{{ $tabela_padrao->TP_ID }}" class="btn btn-default btn-xs disabled">Excluir</a>
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


            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/jquery.dataTables.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.bootstrap.js')}}"></script>
            <script type="text/javascript" language="javascript" src="{{asset('js/bootbox.min.js')}}"></script>

            <script type="text/javascript">

                $(document).ready(function(){

                    $('#listar').DataTable({
                        "language": {
                            "url": "{{asset('js/datatables/Portuguese-Brasil.json')}}"
                        }
                    });

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


