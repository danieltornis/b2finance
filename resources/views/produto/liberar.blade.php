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
                            <h2>Nova Liberação de Produto</h2>
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

                            {!! Form::open([
                                'route' => 'produto.gravar',
                                'class' => 'form-horizontal form-label-left'
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('filial', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('filial', $filial_combo, Input::old('filial'), ['class' => 'form-control select2_single','placeholder' => 'Selecione']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('cliente', 'Cliente', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12 select_status')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="hidden" name="cliente_pesquisada">
                                    {!! Form::select('cliente', [], Input::old('cliente'), ['class' => 'form-control select2_single','placeholder' => 'Selecione']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('produto', 'Produto', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('produto', $produto_combo, Input::old('produto'), ['class' => 'form-control select2_single','placeholder' => 'Selecione']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('cnpj', 'CNPJ', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    {!! Form::text('cnpj', Input::old('cnpj'), array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('ativo', 'Status', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ativo', $ativo_combo, Input::old('ativo'), ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('data_corte', 'Data de Expiração', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::text('data_corte', Input::old('data_corte'), array('class' => 'form-control', 'id' => 'data_corte', 'data-date-format' => 'dd/mm/yyyy', 'data-date-viewmode' => 'years')) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    {{ csrf_field() }}
                                    {!! Form::submit('Salvar', ['class' => 'btn btn-success']) !!}
                                    <a href="{{ route('produto') }}" class="btn btn-primary">Cancelar</a>
                                </div>
                            </div>

                            </form>
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

            $("#data_corte").datepicker(); // Sample

            $('select[name=filial]').on('change', function(){
                var filial_id = this.value;
                $.ajax({
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('produto.ajax.buscarCliente') }}",
                    dataType: 'json',
                    data: {filial_id : filial_id}
                })
                        .done(function(data) {
                            //$("select[name='cliente']").select2("destroy");
                            var $el = $("select[name='cliente']");
                            $el.empty(); // remove old options
                            $el.append($("<option value='' selected='selected'>Selecione</option>"));

                            if (data != '') {
                                $.each(data, function (value, key) {
                                    $el.append($("<option></option>")
                                            .attr("value", key.clt_id).text(key.clt_nome_razao));
                                });
                                var filial_pesquisada = $('input[name=cliente_pesquisada]').val();
                                if(filial_pesquisada > 0) {
                                    $("select[name='cliente']").val(filial_pesquisada);
                                    //$('input[name=cliente_pesquisada]').val('');
                                }
                            }
                            //$("select[name='cliente']").select2();
                            $('#cliente').trigger('change');
                        })
                        .error(function(e){
                            //alert('Ocorreu um erro ao tentar buscar o cliente!');
                        });
                }).trigger('change');
        });
    </script>
@stop
