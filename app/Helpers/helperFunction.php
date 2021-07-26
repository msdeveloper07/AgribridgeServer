<?php

use Illuminate\Support\Facades\Storage;

function base64File($fileName = null, $file = null, $path = null)
{
    $data = $file;
    $pos  = strpos($data, ';');
    $type = explode('/', explode(':', substr($data, 0, $pos))[1])[1];
    $data_ = explode( ',', $file );
    $file_data = Storage::put($path . $fileName . '.' . $type, base64_decode($data_[1]));
    return ($fileName . '.' . $type);
}

function deleteFileFromStorage($path, $fileName)
{
    // $path = 'public/images/hospital/';
    // $fileName = dgsdfsdf_sdfsdff.jpg;
    Storage::delete($path . $fileName);
}

function tokentAuthentication(){
    if (!$user = JWTAuth::parseToken()->authenticate()) {
        return 0;
    }
    return $user;
}
