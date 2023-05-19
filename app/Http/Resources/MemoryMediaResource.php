<?php

namespace App\Http\Resources;

use App\Traits\ApiTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class MemoryMediaResource extends JsonResource
{
    use ApiTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {

        $file = $this->filePath ;
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif','bmp','tif','tiff','webp','svg'];
        $isImage = in_array($extension, $imageExtensions) ;

        $videoExtensions = ['mp4', 'avi', 'mov','mp3','mkv','wmv','flv','webm','mpeg','mpg','3gp','asf'];
        $isVideo = in_array($extension, $videoExtensions) ;

        if ($isVideo) {
            $file_type = 'video' ;
        } else {
            $file_type = 'image' ;
        }
        return [
            'id'                     => $this->id,
            'file'                   => $this->file ? $this->filePath : '',
            'file_type'              => $file_type,
        ];
    }


}
