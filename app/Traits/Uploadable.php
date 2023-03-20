<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;

trait Uploadable
{
    public function uploadFile($file, $domain)
    {
        $directory = public_path('assets/uploads/' . $domain);

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        $path = Uuid::uuid4()->toString() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $path);
        return $path;
    }

    public function deleteFile($file_name, $directory = 'unknown'): void {
        if ($file_name && $file_name != 'default.png' && file_exists("assets/uploads/$directory/$file_name")) {
            unlink("assets/uploads/$directory/$file_name");
        }
    }


}
