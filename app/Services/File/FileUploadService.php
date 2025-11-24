<?php

namespace App\Services\File;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
  /**
   * Upload file to file systems storage
   *
   * @param string $fileName
   * @param FILE $file
   * @param boolean $public
   * @return void
   */
  public function upload($fileName, $file, $public = true)
  {
    if ($public) {
      return $this->publicUpload($fileName, $file);
    }
    return $this->privateUpload($fileName, $file);
  }

  /**
   * Delete file to file systems storage
   *
   * @param string $fileName
   * @param boolean $public
   * @return void
   */
  public function delete($fileName, $public = true)
  {
    if ($public) {
      return $this->publicDelete($fileName);
    }
    return $this->privateDelete($fileName);
  }

  /**
   * Public file upload.
   */
  protected function publicUpload($fileName, $file)
  {
    $path = Storage::disk(config('filesystems.public'))->put($fileName, file_get_contents($file), 'public');
    return $path;
  }

  /**
   * Private file upload.
   */
  protected function privateUpload($fileName, $file)
  {
    $path = Storage::disk(config('filesystems.private'))->put($fileName, file_get_contents($file));
    return $path;
  }
  
  /**
   * Public file delete.
   */
  protected function publicDelete($fileName)
  {
    $path = Storage::disk(config('filesystems.public'))->delete($fileName);
    return $path;
  }

  /**
   * Private file delete.
   */
  protected function privateDelete($fileName)
  {
    $path = Storage::disk(config('filesystems.private'))->delete($fileName);
    return $path;
  }
}
