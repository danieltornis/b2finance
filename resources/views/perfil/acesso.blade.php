@extends('master')

@section('styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/select.dataTables.min.css')  }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/buttons.dataTables.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/jquery.dataTables.min.css')  }}">

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Editar Perfil: {{ $perfil->PER_NOME }}</h2>

                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            {!! Form::model($perfil, array('route' => array('perfil.atualizar_acesso', $perfil->PER_ID), 'method' => 'PUT', 'class' => 'form-horizontal form-label-left')) !!}

                            <table id="listar" class="table table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Acesso</th>
                                    <th>Descrição</th>
                                    <th>Ativo?</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($acesso as $ace)
                                    <tr>
                                        @if (array_search($ace->ACE_ID,$permissao) == $ace->ACE_ID)
                                            <td><input type="checkbox" name="checkbox[]" value="{{ $ace->ACE_ID }}" checked ></td>
                                        @else
                                            <td><input type="checkbox" name="checkbox[]" value="{{ $ace->ACE_ID }}"></td>
                                        @endif
                                        <td>{{ $ace->ACE_PERMISSAO }}</td>
                                        <td>{{ $ace->ACE_DESCRICAO }}</td>
                                        <?php
                                        if($ace->ACE_ATIVO=='S') {
                                            $ativo = 'SIM';
                                        }  else {
                                            $ativo = 'NÃO';
                                        }
                                        ?>
                                        <td>{{ $ativo }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                                    {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('perfil') }}" class="btn btn-primary">Cancelar</a>
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('scripts')

        <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/jquery.dataTables.js')}}"></script>
        <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.bootstrap.js')}}"></script>
        <script type="text/javascript" language="javascript" src="{{asset('js/datatables/js/dataTables.select.min.js')}}"></script>
        <script type="text/javascript" language="javascript" src="{{asset('js/bootbox.min.js')}}"></script>

        <script type="text/javascript">

            $(document).ready(function() {

                var table = $('#listar').DataTable({
                    "scrollY": "380px",
                    "scrollCollapse": true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "language": {
                        "url": "{{asset('js/datatables/Portuguese-Brasil.json')}}"
                    }
                });
            })
        </script>
@stop


