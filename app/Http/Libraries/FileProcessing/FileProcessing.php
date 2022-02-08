<?php

namespace App\Http\Libraries\FileProcessing;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Models\Agreement;
use App\Models\Icon;
use App\Models\Image;
use App\Models\ReportFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Klasa umożliwiająca przetwarzanie plików
 */
class FileProcessing
{
    /**
     * Proces zapisania pliku na serwerze
     * 
     * @param string $entity nazwa encji której dotyczyć ma zapisywany plik
     * @param string $filePath ścieżka do pliku który ma zostać zapisany
     * @param string $folder katalog na dysku w którym ma zostać zapisany plik
     * @param bool $originalSource flaga określająca czy plik ma zostać zapisany bez żadnych modyfikacji
     * @param bool $uploadedByForm flaga określająca czy plik został wgrany poprzez formularz
     * @param string|null $filename nazwa pliku pod jaką ma zostać zapisany plik
     * @param string|null $fileExtension rozszerzenie zapisanego pliku
     * 
     * @return Agreement|Icon|Image|ReportFile
     */
    public static function saveFile(string $entity, string $filePath, string $folder, bool $originalSource, bool $uploadedByForm, ?string $filename = null, ?string $fileExtension = null) {

        if ($fileExtension === null) {

            if ($uploadedByForm) {
                $pathParts = pathinfo($filePath);
                $fileExtension = '.' . $pathParts['extension'];
            } else {

                $fileUrlHeaders = get_headers($filePath, 1);
                $fileContentType = $fileUrlHeaders['Content-Type'];

                if (is_array($fileContentType)) {
                    $fileContentType = $fileContentType[0];
                }

                $fileExtensions = explode('/', $fileContentType);
                $fileExtensionsLength = count($fileExtensions);
                $fileExtension = '.' . $fileExtensions[$fileExtensionsLength-1];
            }
        } else {
            $fileExtension = '.' . $fileExtension;
        }

        if ($filename === null) {
            $encrypter = new Encrypter;
            $filename = $encrypter->generateToken(64, Image::class, 'filename', $fileExtension);
        } else {
            if (!Validation::checkUniqueness($filename, Image::class, 'filename')) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    'Taka nazwa pliku jest już zajęta'
                );
            }
        }

        $fileContents = file_get_contents($filePath);
        $fileDestination = $folder . '/' . $filename;

        if ($originalSource) {
            Storage::put($fileDestination, $fileContents);
        } else {
            switch ($fileExtension) {
                case '.jpeg':
                    $uploadedImage = imagecreatefromstring($fileContents);
                    $imageWidth = imagesx($uploadedImage);
                    $imageHeight = imagesy($uploadedImage);
                    $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
                    imagecopyresampled($newImage, $uploadedImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);
                    imagejpeg($newImage, 'storage/' . $fileDestination, 100); // TODO Potestować ile maksymalnie można zmniejszyć jakość obrazu, żeby nadal był akceptowalny
                    break;
            }
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        switch ($entity) {
            case 'avatar':
                /** @var Image $image */
                $image = new Image;
                $image->imageable_type = 'App\Models\User';
                $image->imageable_id = $user->id;
                $image->filename = $filename;
                $image->creator_id = $user->id;
                $image->visible_at = now();
                $image->save();
                $file = $image;
                break;
        }

        return $file;
    }

    /**
     * Proces zapisania zdjęcia profilowego na serwerze
     * 
     * @param string $avatarPath ścieżka do zdjęcia które ma zostać zapisane
     * @param bool $uploadedByForm flaga określająca czy plik został wgrany poprzez formularz
     * 
     * @return Image
     */
    public static function saveAvatar(string $avatarPath, bool $uploadedByForm): Image {
        return self::saveFile('avatar', $avatarPath, 'user-pictures', false, $uploadedByForm, null, 'jpeg');
    }

    /**
     * Proces zapisania zdjęcia profilowego na serwerze
     * 
     * @param string $avatarPath ścieżka do zdjęcia które ma zostać zapisane
     * 
     * @return Image
     */
    public static function saveLogo(string $avatarPath): Image {
        return self::saveFile('logo', $avatarPath, 'partner-logos', false, true, null, 'jpeg');
    }
}
