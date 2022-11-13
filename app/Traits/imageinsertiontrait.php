<?php

namespace App\Traits;
use Symfony\Component\HttpFoundation\File\File;

trait imageinsertiontrait
{
    public function imageinsertion($image, $folder)
    {
        $file_extension = $image->getClientOriginalExtension();
        $file_name = rand(1,100).time() .rand(1,100). '.' . $file_extension;
        $allowedextensions = ['jpg','jpeg','png','gif'];
        if(in_array($file_extension,$allowedextensions)){
            $path = $folder;
            $image->move($path, $file_name);
            return $file_name;
        }else{
            return 'error';
        }
    }
}
