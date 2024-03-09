<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function download(Request $request){
        return response()->download(storage_path('app/' . $request->file ));
    }

    public function image(Request $request){
        
        $file = storage_path('app/' . $request->file );
        $ext = pathinfo($request->file, PATHINFO_EXTENSION);

        if($ext == 'png' || 'PNG'){
        $headers = array(
            'Content-Type:image/png',
            );
        }

        else if($ext == 'jpg' || 'jpeg' || 'JPEG' || 'JPG'){
        $headers = array(
            'Content-Type:image/jpeg',
            );
        }

        else if($ext == 'gif' || 'GIF'){
        $headers = array(
            'Content-Type:image/gif',
            );
        }

        $response = response()->download($file, 'logo.'.$ext , $headers);
        return $response;
    }
}
