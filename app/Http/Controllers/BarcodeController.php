<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/22/2018
 * Time: 9:56 AM
 */

namespace App\Http\Controllers;


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function adminGenerateQrCode($code){
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
        ]);

        // invoke a fresh QRCode instance
        $qrcode = new QRCode($options);

        // and dump the output
        return $qrcode->render($code);
    }
}
