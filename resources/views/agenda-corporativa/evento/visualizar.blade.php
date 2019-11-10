<div style="width:60%;">

    <div class="" role="main">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Evento - Visualizar</h2>
                        <div class="pull-right">
                            <a class="btn btn-primary btn-xs" onclick="javascript:$.fancybox.close()"><i class="fa fa-close"> Fechar</i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content form-horizontal form-label-left">
                        <div class="form-group">
                            {!! Form::label('data', 'Data', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->date->format('d/m/Y') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('consultor', 'Consultor', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->consultor->name   }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('tipo_agenda', 'Tipo da Agenda', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->typeDescricao() }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('filial_modal', 'Filial', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->consultor->filial->filial_descricao }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('cliente_modal', 'Cliente', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->cliente->clt_nome_razao }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('projeto_modal', 'Projeto', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->projeto->equip_nome }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('horario_inicio', 'Horário Início', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label class="form-control-static">{{ $evento->starthour }}</label>
                            </div>
                            {!! Form::label('horario_fim', 'Horário Fim', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label class="form-control-static">{{ $evento->endhour }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('local', 'Local', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12 select_status')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->location }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('atividade', 'Atividade', array('class' => 'control-label col-md-3 col-sm-3 col-xs-12')) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <label class="form-control-static">{{ $evento->description }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>