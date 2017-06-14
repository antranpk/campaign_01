<?php
namespace App\Traits\Common;

use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;

trait UploadableTrait
{
    public function uploadableTimestamp()
    {
        return 'Y/m';
    }

    public function uploadFile(UploadedFile $file, $path = 'other', $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);
        $target = $this->getTarget($path);
        $fileName = $this->getFileName($file);
        $destinationTarget = $target . '/' . $fileName;
        $count = 0;

        while ($storage->has($destinationTarget)) {
            $count++;
            $destinationTarget = $target . '/' . $count . '-' . $fileName;
        }

        $saved = $storage->put($destinationTarget, file_get_contents($file));

        if (!$saved) {
            return false;
        }

        return $destinationTarget;
    }

    public function destroyFile($destinationTarget, $uploadDisk = 'image')
    {
        $storage = Storage::disk($uploadDisk);

        if ($storage->has($destinationTarget)) {
            return $storage->delete($destinationTarget);
        }
    }

    public function getTarget($path)
    {
        if (method_exists($this, 'uploadableTimestamp') && is_string($this->uploadableTimestamp())) {
            $path = Carbon::now()->format($this->uploadableTimestamp()) . '/' . $path;
        }

        return $path;
    }

    public function getFileName(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $name = uniqid('img-' . time());
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        return $name . '.' . $ext;
    }

    public function makeDataMedias($data, $path = 'other')
    {
        if (!is_array($data)) {
            throw new UnknowException('Data type is incorrect');
        }

        if (!empty($data['image'])) {
            foreach ($data['image'] as $value) {
                $result[] = [
                    'type' => Media::IMAGE,
                    'url_file' => $this->uploadFile($value, $path),
                ];
            }
        }

        if (!empty($data['video'])) {
            foreach ($data['video'] as $value) {
                $result[] = [
                    'type' => Media::VIDEO,
                    'url_file' => $value,
                ];
            }
        }

        return $result;
    }
}
