@extends('master')

@section('styles')
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
                            <h2>Ambiente</h2>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    <h4 class="alert-heading">Erros!</h4>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} </li>
                                    @endforeach
                                </ul>
                            @endif

                            {!! Form::open([
                            'route' => 'ambiente',
                            'method' => 'POST',
                            'class' => 'form-horizontal form-label-left',
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('filial_sel', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="filial_pesquisada" value="{{ $filial_sel }}">
                                    {!! Form::select('filial_sel', $filial, $filial_sel, ['class' => 'form-control select2_single','placeholder' => '--selecione--']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {!! Form::submit('Ok', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('index') }}" class="btn btn-primary">Cancelar</a>
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
                        allowClear: true
                    });
                });
            </script>
@stop


