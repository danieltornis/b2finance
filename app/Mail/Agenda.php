<?php

namespace App\Mail;

use App\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Agenda extends Mailable
{
    use Queueable, SerializesModels;

    /**
     *
     * inclusao / ediicao / exclusao
     *
     * @var
     *
     */
    protected $tipo_email;
    protected $evento;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Evento $evento, $tipo_email)
    {
        $this->evento       = $evento;
        $this->tipo_email   = $tipo_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // titulo do email
        $titulo = '';
        $titulo_corpo = '';

        if($this->tipo_email == 'inclusao') {
            $titulo         = 'Inclusão';
            $titulo_corpo   = 'Você está recebendo este email porque houve a Inserção de um Evento que envolve você na Agenda Corporativa da b2finance.';

        } elseif($this->tipo_email == 'edicao') {
            $titulo         = 'Edição';
            $titulo_corpo   = 'Você está recebendo este email porque houve a Atualização de um Evento que envolve você na Agenda Corporativa da b2finance.';

        } elseif($this->tipo_email == 'exclusao') {
            $titulo         = 'Exclusão';
            $titulo_corpo   = 'Você está recebendo este email porque houve a Exclusão de um Evento que envolve você na Agenda Corporativa da b2finance.';
        }

        $cliente = $this->evento->cliente;
        $data    = $this->evento->date;

        if($cliente) {
            $titulo .= ' Agenda '.$cliente->clt_nome_razao.' - '.$data->format('d/m/Y');
        } else {
            $titulo .= ' Agenda '.$data->format('d/m/Y');
        }

        return $this->view('emails.agenda')
            ->subject($titulo)
            ->with([
                'evento'        => $this->evento,
                'cliente'       => $cliente,
                'tipo_email'    => $this->tipo_email,
                'titulo_corpo'  => $titulo_corpo
            ]);
    }
}
