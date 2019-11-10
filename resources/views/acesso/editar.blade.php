@extends('master')

@section('styles')

    <link href="{{ asset('css/select/select2.min.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="right_col" role="main">
        <div class="">

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Editar Acesso</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            <!-- if there are creation errors, they will show here -->
                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    <h4 class="alert-heading">Erros!</h4>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            {!! Form::model($acesso, array('route' => array('acesso.atualizar', $acesso->ACE_ID), 'method' => 'PUT', 'class' => 'form-horizontal form-label-left')) !!}


                            <div class="form-group">
                                {!! Form::label('permissao', 'Permissão', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::text('permissao', $acesso->ACE_PERMISSAO, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('descricao', 'Descrição', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::text('descricao', $acesso->ACE_DESCRICAO, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('ativo', 'Ativo?', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('ativo', ['S'=>'Sim','N'=>'Não'],$acesso->ACE_ATIVO, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-2">
                                    {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('acesso') }}" class="btn btn-primary">Cancelar</a>
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

        <!-- select2 -->
    <script src="{{ asset('js/select/select2.full.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){

            $(".select2_single").select2({
                allowClear: false
            });

        });

    </script>

@stop


