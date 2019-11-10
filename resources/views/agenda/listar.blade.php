@extends('master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/dataTables.bootstrap.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('js/context_menu/dist/jquery.contextMenu.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/media/css/select.dataTables.min.css')  }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/buttons.dataTables.min.css')  }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables/css/jquery.dataTables.min.css')  }}">

    <!-- select2 -->
    <link href="{{ asset('js/select2-4.0.3/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/calendar/fullcalendar.min.css') }}" rel="stylesheet">
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel" style="height:100%;">
                        <div class="x_title">
                            <h2>Agenda <small>Filtros</small></h2>
                            <ul class="nav navbar-left panel_toolbox">
                                <li>
                                    <a class="collapse-link" id="esconder_filtros"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            @include('flash::message')

                            {!! Form::open([
                            'route' => 'agenda',
                            'class' => 'form-horizontal form-label-left',
                            'id'    => 'form_filtro'
                            ]) !!}

                            <div class="form-group">
                                {!! Form::label('mes', 'Mês', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('mes', $mes, $mes_selecionado, ['class' => 'form-control select2_single']) !!}
                                </div>

                                {!! Form::label('ano', 'Ano', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    {!! Form::select('ano', $ano, $ano_selecionado, ['class' => 'form-control select2_single']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('consultor', 'Consultor', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('consultor[]', $consultores_combo, $consultor, ['class' => 'form-control select2_single', 'multiple' => 'multiple']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('filial', 'Filial', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('filial[]', $filiais, $filial, ['class' => 'form-control select2_single', 'multiple' => 'multiple']) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('especialidade', 'Especialidade', array('class' => 'control-label col-md-2 col-sm-2 col-xs-12')) !!}
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::select('especialidade[]', $especialidades, $especialidade, ['class' => 'form-control select2_single', 'multiple' => 'multiple']) !!}
                                </div>
                            </div>

                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <input type="hidden" name="filtrar" value="sim">
                                    {!! Form::submit('Filtrar', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">

                            <div class="alert alert-error" id="erro_carregar_eventos" style="display: none">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Erro ao carregar Eventos!
                            </div>

                            <div id='calendar'></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('scripts')

            <script type="text/javascript" src="{{ asset('js/moment-2-14.min.js') }}"></script>
            <script type="text/javascript" src="{{asset('js/calendar/fullcalendar.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('js/calendar/locale/pt-br.js')}}"></script>

            <!-- select2 -->
            <script src="{{ asset('js/select2-4.0.3/dist/js/select2.min.js') }}"></script>

            <script type="text/javascript">

                $(document).ready(function(){

                    $(".select2_single").select2({
                        allowClear: false
                    });


                    //$('#esconder_filtros').trigger('click');

                    var calendar = $('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay,listMonth'
                        },
                        locale: 'pt-br',
                        selectable: true,
                        selectHelper: true,
                        editable: false,
                        defaultDate: new Date($('#ano').val(), $('#mes').val() - 1, 1),
                        events: {
                            url: '{{ route('agenda.eventos-json') }}',
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                consultor:      $('select[name="consultor[]"]').val(),
                                filial:         $('select[name="filial[]"]').val(),
                                especialidade:  $('select[name="especialidade[]"]').val()
                            },
                            error: function() {
                                //alert('erro');
                                $('#erro_carregar_eventos').show();
                            },
                            success: function() {
                                $('#erro_carregar_eventos').hide();
                            }
                        },
                        eventClick: function(event) {
                            if (event.url) {
                                window.open(event.url, '_blank');
                                return false;
                            }
                        }
                    });
                });

            </script>

@stop


