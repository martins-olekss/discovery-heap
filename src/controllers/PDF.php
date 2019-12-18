<?php
namespace App\Controllers;

use Dompdf\Dompdf;

class PDF {

    public static function test() {
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
    }
}