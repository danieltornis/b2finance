@extends('master')

@section('styles')

    <style>
        .selected {
            background-color: #FFD700 !important;
        }
    </style>
@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_title">
                            <h2>
                                Evento
                            </h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Consultor</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">{{ $evento->consultor->name }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Filial</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        @if(!empty($evento->consultor->filial))
                                            {{ $evento->consultor->filial->filial_descricao }}
                                        @else
                                            -
                                        @endif
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Especialidade</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        @if(!empty($evento->consultor->especialidade))
                                            {{ $evento->consultor->especialidade->descricao }}
                                        @else
                                            -
                                        @endif
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Data</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        {{ $evento->date->format('d/m/Y') }}
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Inicial</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">{{ $evento->starthour }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Hora Final</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">{{ $evento->endhour }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Descrição</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">
                                        @if(!empty($evento->description))
                                            {{ $evento->description }}
                                        @else
                                            -
                                        @endif

                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Cliente</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <!--<label class="control-label">{ { $evento->cliente->clt_nome_razao }}</label>-->
                                    <label class="control-label">{{ $evento->typeDescricao() }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Localização</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="control-label">{{ $evento->locationDescricao() }}</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('scripts')

            <script type="text/javascript">

                $(document).ready(function(){


                });

            </script>

@stop


