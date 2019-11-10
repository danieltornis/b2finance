@extends('master')

@section('styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">

    <!-- select2 -->
    <link href="{{ asset('css/select/select2.min.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Usuário vs Perfil</h2>

                            <div class="pull-right">
                                <!-- Split button -->

                                <div class="btn-group">
                                    @can('usuario_perfil_cadastrar')
                                        <a href="{{ route('perfilusuario.perfilusuariocadastrar') }}" type="button" class="btn btn-primary">Novo Usuário vs Perfil</a>
                                    @else
                                        <a href="#" type="button" class="btn btn-default btn-xs disabled">Novo Usuário vs Perfil</a>
                                    @endcan
                                </div>

                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @include('flash::message')

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    <h4 class="alert-heading">Erros!</h4>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>
                            @endif

                            {!! Form::open([
                                'route' => 'perfilusuario',
                                'class' => 'form-horizontal form-label-left'
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('usuario', 'Usuário', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('usuario', $usuario_options, $usuario, ['class' => 'form-control select2_single','placeholder' => 'Todos']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('perfil', 'Perfil', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('perfil', $perfil_options, $perfil, ['class' => 'form-control select2_single','placeholder' => 'Todos']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {{ csrf_field() }}
                                    {!! Form::submit('Filtrar', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                            <table id="listar" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Perfil</th>
                                    <th style="max-width: 40px">Excluir</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($perfil_usuarios as $perfil_usuario)
                                    <tr>
                                        <td>{{ $perfil_usuario->name }}</td>
                                        <td>{{ $perfil_usuario->PER_NOME }}</td>
                                        <td>
                                            @can('usuario_perfil_excluir')
                                                <a id="excluir-{{ $perfil_usuario->PU_ID }}" class="btn btn-danger btn-xs">Excluir</a>
                                                {!! Form::model($perfil_usuario, array('route' => array('perfilusuario.excluir', $perfil_usuario->PU_ID), 'id' => 'form-'.$perfil_usuario->PU_ID)) !!}
                                                {!! Form::hidden('_method', 'DELETE') !!}
                                                {!! Form::submit('Excluir', array('class' => 'btn btn-danger btn-xs','style' => 'display:none' )) !!}
                                                {!! Form::close() !!}
                                            @else
                                                <a id="excluir-{{ $perfil_usuario->PU_ID }}" class="btn btn-default btn-xs disabled">Excluir</a>
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

            <!-- select2 -->
            <script src="{{ asset('js/select/select2.full.js') }}"></script>


            <script type="text/javascript">

                $(document).ready(function(){

                    $('#listar').DataTable({
                        "language": {
                            "url": "{{asset('js/datatables/Portuguese-Brasil.json')}}"
                        }
                    });

                    $(".select2_single").select2({
                        allowClear: true
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


