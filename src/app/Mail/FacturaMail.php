<?php

namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $venta;

    public function __construct($venta)
    {
        $this->venta = $venta;
    }

    public function build()
    {
        $pdf = Pdf::loadView('ventas.factura', [
            'venta' => $this->venta
        ]);

        return $this->subject('Factura de su compra #' . $this->venta->id)
            ->view('emails.factura')
            ->attachData($pdf->output(), 'factura_' . $this->venta->id . '.pdf');
    }
}