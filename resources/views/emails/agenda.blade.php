<p>Olá,</p>

<p>{{ $titulo_corpo }}</p>

<p>
    @if($cliente)
        Cliente: {{ $cliente->clt_nome_razao }}
    @else
        Cliente:
    @endif
    <br />
    Data: {{ $evento->date->format('d/m/Y') }}
</p>

<p>
    Hora Inicio: {{ $evento->starthour }}
    <br />
    Hora Fim: {{ $evento->endhour }}
    <br />
    Local: {{ $evento->locationDescricao() }}
    <br />
    Descrição:
    <br />
    {!! utf8_decode(nl2br($evento->description)) !!}
</p>

<p>
    Log Inclusão: {{ $evento->login_inc }}
    @if($tipo_email == 'edicao' or $tipo_email == 'exclusao')
        <br />
        Log Edição: {{ $evento->login_inc }}
        @if($tipo_email == 'exclusao')
            <br />
            Log Exclusão: {{ auth()->user()->login }}
        @endif
    @endif
</p>


<p>Caso queira acessar informações mais detalhadas, favor acessar a Agenda através do Timesheet.</p>