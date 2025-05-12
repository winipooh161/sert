<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class PublicCertificateController extends Controller
{
    /**
     * Отображает публичную страницу сертификата по UUID.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $certificate = Certificate::where('uuid', $uuid)->firstOrFail();
        
        return view('certificates.public', compact('certificate'));
    }
}
