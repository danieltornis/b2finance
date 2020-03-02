@extends('master')

@section('styles')

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
                            <h2>Editar Link</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <!-- if there are creation errors, they will show here -->

                            @include('flash::message')

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    <h4 class="alert-heading">Erros!</h4>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>
                            @endif

                            {!! Form::model($link, array('route' => array('link.atualizar', $link->LIN_ID), 'method' => 'PUT', 'class' => 'form-horizontal form-label-left')) !!}

                            <div class="form-group">
                                {!! Form::label('codigo', 'Código', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('codigo', $link->LIN_CODIGO, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('versao', 'Versão', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('versao', $link->LIN_VERSAO, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('produto', 'Produto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('produto', $produto_combo, $link->LIN_PRODUTO, ['class' => 'form-control select2_single','placeholder' => 'Selecione']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('link', 'Link', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('link', $link->LIN_LINK, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('data_corte', 'Data de Expiração', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('data_corte', $data_corte, array('class' => 'form-control', 'id' => 'data_corte', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {{ csrf_field() }}
                                    {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('link') }}" class="btn btn-primary">Cancelar</a>
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

    <script type="text/javascript" src="{{ asset('js/bootstrap/bootstrap-datepicker.js') }}"></script>

    <!-- select2 -->
    <script src="{{ asset('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $(".select2_single").select2({
                allowClear: false
            });

            $("#data_corte").datepicker();
        });
    </script>
@stop
