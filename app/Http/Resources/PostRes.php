<?php
//membantu memberikan pesan eror dan lainlain
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostRes extends JsonResource
{
    //mendefinisikan properti
    public $status;
    public $message;
    public $resource;

    public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }


    public function toArray(Request $request): array
    {
        return [
            'succes' => $this->status,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
