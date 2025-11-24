<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\File\FileUploadService;

class FileUploadFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return FileUploadService::class;
  }
}
