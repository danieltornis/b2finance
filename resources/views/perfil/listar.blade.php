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
                            <h2>Perfil</h2>

                            <div class="pull-right">
                                <!-- Split button -->

                                <div class="btn-group">
                                    @can('perfil_cadastrar')
                                        <a href="{{ route('perfil.cadastrar') }}" type="button" class="btn btn-primary">Novo Perfil</a>
                                    @else
                                        <a href="{{ route('perfil.cadastrar') }}" type="button" class="btn btn-default btn-xs disabled">Novo Perfil</a>
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
                                    <th>Nome</th>
                                    <th style="max-width: 40px">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($perfis as $perfil)
                                    <tr>
                                        <td>{{ $perfil->PER_NOME }}</td>
                                        <td>
                                            @can('perfil_editar')
                                                <a href="{{ route('perfil.editar', $perfil->PER_ID) }}" class="btn btn-info btn-xs">Editar</a>
                                            @else
                                                <a href="{{ route('perfil.editar', $perfil->PER_ID) }}" class="btn btn-default btn-xs disabled">Editar</a>
                                            @endcan

                                            @can('acesso_perfil_cadastrar')
                                                <a href="{{ route('perfil.acesso', $perfil->PER_ID) }}" class="btn btn-primary btn-xs">Acesso</a>
                                            @else
                                                <a href="{{ route('perfil.acesso', $perfil->PER_ID) }}" class="btn btn-default btn-xs disabled">Acesso</a>
                                            @endcan

                                            @can('perfil_excluir')
                                                <a id="excluir-{{ $perfil->PER_ID }}" class="btn btn-danger btn-xs">Excluir</a>
                                                {!! Form::model($perfil, array('route' => array('perfil.excluir', $perfil->PER_ID), 'id' => 'form-'.$perfil->PER_ID)) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::submit('Excluir', array('class' => 'btn btn-danger btn-xs','style' => 'display:none' )) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <a id="excluir-{{ $perfil->PER_ID }}" class="btn btn-default btn-xs disabled">Excluir</a>
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


