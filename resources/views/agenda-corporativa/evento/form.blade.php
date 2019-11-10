<div style="width:60%;">

    <div class="" role="main">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Evento</h2>
                        <div class="pull-left">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="pull-right">
                            @can('agenda_excluir')
                                @if($evento)
                                    <a href="{{ route('agenda-corporativa.excluir', $evento->id) }}" class="btn btn-danger btn-xs btn_excluir"><i class="fa fa-trash"> Excluir Evento</i></a>
                                @endif
                            @endcan
                            <a class="btn btn-primary btn-xs" onclick="javascript:$.fancybox.close()"><i class="fa fa-close"> Fechar</i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="alert-box">
                            @include('flash::message')
                        </div>
                        {!! Form::open([
                            'id'        => 'modal_form_evento',
                            'class'     => 'form-horizontal form-label-left',
                        ]) !!}

                        <div class="form-group">
                            {!! Form::label('data', 'Data', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="data" class="form-control" readonly value="{{ ($evento and $evento->date) ? $evento->date->format('d/m/Y') : $data->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('consultor', 'Consultor', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control" readonly value="{{ $consultor->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('tipo_agenda', 'Tipo da Agenda', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::select('tipo_agenda', $tipos_agenda, ($evento) ? $evento->type : '', ['class' => 'form-control','placeholder' => 'Selecione']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('filial_modal', 'Filial', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::select('filial_modal', $filiais_combo, ($evento && $evento->cliente) ? $evento->cliente->filial->filial_cod : '', ['class' => 'form-control select2_single']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cliente_modal', 'Cliente', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="cliente_pesquisada_modal" value="{{ ($evento) ? $evento->client : '' }}">
                                {!! Form::select('cliente_modal', [], ($evento) ? $evento->client : '', ['class' => 'form-control','placeholder' => 'Selecione']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('projeto_modal', 'Projeto', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="hidden" name="projeto_pesquisada_modal" value="{{ ($evento) ? $evento->project : '' }}">
                                {!! Form::select('projeto_modal', [], null, ['class' => 'form-control select2_single','placeholder' => 'Todos']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('horario_inicio', 'Horário Início', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                {!! Form::text('horario_inicio', ($evento) ? $evento->starthour : '08:30', array('class' => 'form-control')) !!}
                            </div>
                            {!! Form::label('horario_fim', 'Horário Fim', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                {!! Form::text('horario_fim', ($evento) ? $evento->endhour : '18:00', array('class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('local', 'Local', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::select('local', $locais, ($evento) ? $evento->location : '', ['class' => 'form-control','placeholder' => 'Selecione']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('atividade', 'Atividade', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::textarea('atividade', ($evento) ? $evento->description : '', array('class' => 'form-control', 'rows' => '3')) !!}
                            </div>
                        </div>

                        @if(!isset($evento))
                            <div class="form-group">
                                {!! Form::label('repete', 'Repete?', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <label><input type="radio" name="repete" value="nao" class="control-label" checked> Não</label>
                                    <label><input type="radio" name="repete" value="sim" class="control-label"> Sim</label>
                                </div>
                                <div class="conteudo_repetir_sim">
                                    {!! Form::label(null, 'Até', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        {!! Form::text('repetir_ate', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group conteudo_repetir_sim">
                                {!! Form::label('dias_semana', 'Dias da Semana', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <label><input type="checkbox" name="dia_semana[]" value="segunda"> Segunda&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="terca"> Terça&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="quarta"> Quarta&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="quinta"> Quinta&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="sexta"> Sexta&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="sabado"> Sábado&nbsp;&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="dia_semana[]" value="domingo"> Domingo&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('todos_consultores', 'Todos Consultores?', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label><input type="radio" name="todos_consultores" value="nao" class="control-label" checked> Não</label>
                                    <label><input type="radio" name="todos_consultores" value="sim" class="control-label"> Sim</label>
                                </div>
                            </div>

                        @endif

                        <div class="form-group">
                            {!! Form::label(null, 'Log Inclusão', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ ($evento) ? $evento->login_inc : '' }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label(null, 'Log Alteração', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ ($evento) ? $evento->login_alt : '' }}</label>
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                {{ csrf_field() }}

                                <input type="hidden" name="consultor_id" value="{{ ($consultor) ? $consultor->id : '' }}">
                                <input type="hidden" name="evento_id" value="{{ ($evento) ? $evento->id : '' }}">

                                <button type="submit" class="btn btn-success">Salvar</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- /.row -->
    <script type="text/javascript">
        $(document).ready(function(){

            $('.conteudo_repetir_sim').hide();
            $('input[name=horario_inicio], input[name=horario_fim]').inputmask('99:99');
            $('input[name=repetir_ate]').inputmask('99/99/9999');

            $('input[name=repete]').on('click', function(){
                value = this.value;
                if(value == "sim") {
                    $('.conteudo_repetir_sim').show();
                } else {
                    $('.conteudo_repetir_sim').hide();
                }
            });

            $('select[name=filial_modal]').on('change', function(){
                var filial_id = this.value;
                $.ajax({
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: '{{ route('relCO.ajax.buscarCliente') }}',
                    dataType: 'json',
                    data: {filial_id : filial_id}
                })
                    .done(function(data) {
                        var $el = $("select[name='cliente_modal']");
                        $el.empty(); // remove old options
                        $el.append($("<option value='' selected='selected'>Todos</option>"));
                        if (data != '') {
                            $.each(data, function (value, key) {
                                $el.append($("<option></option>")
                                    .attr("value", key.clt_id).text(key.clt_nome_razao));
                            });
                            var filial_pesquisada = $('input[name=cliente_pesquisada_modal]').val();
                            if(filial_pesquisada > 0) {
                                $("select[name='cliente_modal']").val(filial_pesquisada);
                            }
                        }
                        $('select[name=cliente_modal]').trigger('change');
                    })
                    .error(function(e){
                        //alert('Ocorreu um erro ao tentar buscar o cliente!');
                    });
            }).trigger('change');

            $('select[name=cliente_modal]').on('change', function(){
                var cliente_id = this.value;
                console.log(cliente_id);
                $.ajax({
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('agenda-corporativa.ajax.buscarProjeto') }}",
                    dataType: 'json',
                    data: {
                        cliente_id : cliente_id
                    }
                })
                .done(function(data) {
                    console.log('ajax relCo');
                    //$("select[name='projeto']").select2("destroy");
                    var $el = $("select[name='projeto_modal']");
                    $el.empty(); // remove old options
                    $el.append($("<option value='' selected='selected'>Todos</option>"));

                    if (data != '') {
                        $.each(data, function (value, key) {
                            $el.append($("<option></option>")
                                .attr("value", key.equip_id).text(key.equip_desc));
                        });
                        var projeto_pesquisada_modal = $('input[name=projeto_pesquisada_modal]').val();
                        if(projeto_pesquisada_modal > 0) {
                            $("select[name='projeto_modal']").val(projeto_pesquisada_modal);
                            //$('input[name=projeto_pesquisada]').val('');
                        }
                    }
                    //$("select[name='projeto']").select2();
                })
                .error(function(e){
                    //alert('Ocorreu um erro ao tentar buscar o projeto!');
                });
            }).trigger('change');

            form_selector = '#modal_form_evento';
            $(form_selector).on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'agenda-corporativa/evento/gravar',
                    dataType: 'json',
                    data: $(form_selector).serialize(), // serializes the form's elements.
                    success: function(data) {
                        if (data.erros) {

                            html = '<ul>';
                            $.each(data.erros, function( key, value ) {
                                html += '<li>'+value+'</li>';
                            });
                            html += '</ul>';

                            $('#alert-box').html(
                                $('<div class="alert alert-danger">').html(html).fadeIn('slow')
                            );
                        } else {
                            $('#alert-box').html(
                                $('<div class="alert alert-success">').html(data.msg).fadeIn('slow')
                            );
                            $(form_selector)[0].reset();
                            atualizar_agenda_corporativa();
                            setTimeout(function(){
                                $.fancybox.close();
                            }, 1000);
                        }
                    },
                    error: function(requestObject, error, errorThrown){
                        $('#alert-box').html(
                            $('<div class="alert alert-danger">').html('Erro: '+errorThrown).fadeIn('slow')
                        );
                    }
                });
            });

            $('.btn_excluir').on('click', function(e){
                e.preventDefault();

                var r = confirm("Tem certeza que deseja excluir?");
                if(r == true) {
                    $.ajax({
                        type: "POST",
                        url: $(this).attr('href'),
                        method: 'DELETE',
                        dataType: 'json',
                        data: $(form_selector).serialize(), // serializes the form's elements.
                        success: function(data) {
                            if (data.erros) {
                                html = '<ul>';
                                $.each(data.erros, function( key, value ) {
                                    html += '<li>'+value+'</li>';
                                });
                                html += '</ul>';
                                $('#alert-box').html(
                                    $('<div class="alert alert-danger">').html(html).fadeIn('slow')
                                );
                            } else {
                                $('#alert-box').html(
                                    $('<div class="alert alert-success">').html(data.msg).fadeIn('slow')
                                );
                                atualizar_agenda_corporativa();
                                setTimeout(function(){
                                    $.fancybox.close();
                                }, 500);
                            }
                        },
                        error: function(requestObject, error, errorThrown){
                            $('#alert-box').html(
                                $('<div class="alert alert-danger">').html('Erro: '+errorThrown).fadeIn('slow')
                            );
                        }
                    });
                }
            });
        })

    </script>
</div>