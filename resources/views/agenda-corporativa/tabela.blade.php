<table id="tabela_datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
    <tr style="background-color: #cccccc; color: black;">
        <th>Consultor</th>
        @foreach($datas as $data)
            <th>
                {{ $data['dia_semana'] }}
                <br />
                {{ $data['formatado'] }}
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($dados_tabela as $row)
            <tr>
                <td>{!! $row['consultor']  !!}</td>
                @foreach($datas as $data_key => $data)
                    <td class="text-center" style="background-color: {{ $row['filial_cor'] }}">
                        {!! $row[$data_key]  !!}
                        <br />
                        <br />
                        @can('agenda_incluir')
                            <a class="btn btn-default btn-xs"
                               data-fancybox
                               data-type="ajax"
                               data-hideOnContentClick="true"
                               data-showCloseButton="true"
                               data-src="{{ route('agenda-corporativa.evento.novo', [$row['consultor_id'], $data_key]) }}"
                            ><i class="fa fa-plus"></i> Novo Evento</a>
                        @endcan
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<input type="hidden" name="aux_primeira_data_tabela" value="{{ array_shift($datas)['formatado'] }}">