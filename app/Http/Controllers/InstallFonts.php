<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use PDF;
use TCPDF_FONTS;

class InstallFonts extends BaseController
{

    public function index()
    {
        if(file_exists(public_path('asset/images/temMedia/osboha_63c7bfcf724c3.png'))){
        unlink(public_path('asset/images/temMedia/osboha_63c7bfcf724c3.png'));
        }else{
        dd('File does not exists.');
        }
        // $path = '/var/www/html/osboha-certificates-backend/vendor/tecnickcom/tcpdf/fonts/arial.ttf';
        // $test = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 32);
        // print_r($test);


        // $path = '/var/www/html/osboha-certificates-backend/vendor/tecnickcom/tcpdf/fonts/arialbd.ttf';
        // $test = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 32);
        // print_r($test);


        // $path = '/var/www/html/osboha-certificates-backend/vendor/tecnickcom/tcpdf/fonts/calibri.ttf';
        // $test = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 32);
        // print_r($test);


        // $path = '/var/www/html/osboha-certificates-backend/vendor/tecnickcom/tcpdf/fonts/calibrib.ttf';
        // $test = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 32);
        // print_r($test);
    }
}
