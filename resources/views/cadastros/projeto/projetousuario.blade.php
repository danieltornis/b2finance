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
                            <h2>Projeto -> Acesso <a href="{{ route('projeto') }}" type="button" class="btn btn-default"><i class="fa fa-exchange"></i> {{ $projeto }}</a></h2>

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
                                'route' => 'projetousuario.incluir',
                                'class' => 'form-horizontal form-label-left'
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('usuario', 'Usuário', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('usuario', $usuarios, Input::old('usuario'), ['class' => 'form-control select2_single','placeholder' => 'Selecione']) !!}
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {{ csrf_field() }}
                                    {!! Form::hidden('projeto',$projeto_id) !!}
                                    {!! Form::submit('Incluir', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            {!! Form::close() !!}

                            <table id="listar" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th style="max-width: 40px">Excluir</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissao as $per)
                                    <tr>
                                        <td>{{ $per->name }}</td>
                                        <td>
                                            <a id="excluir-{{ $per->id }}" class="btn btn-danger btn-xs">Excluir</a>
                                            {!! Form::model($per, array('route' => array('projetousuario.excluir', $per->id, $projeto_id ), 'id' => 'form-'.$per->id)) !!}
                                            {!! Form::hidden('_method', 'DELETE') !!}
                                            {!! Form::submit('Excluir', array('class' => 'btn btn-danger btn-xs','style' => 'display:none' )) !!}
                                            {!! Form::close() !!}
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


