<?php

namespace App\Http\Libraries\FileProcessing;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

/**
 * Klasa umożliwiająca przetwarzanie plików
 */
class FileProcessing
{
    /**
     * Proces zapisania pliku na serwerze
     * 
     * @param string $filePath ścieżka do pliku który ma zostać zapisany
     * @param string $folder katalog na dysku w którym ma zostać zapisany plik
     * @param bool $originalSource flaga określająca czy plik ma zostać zapisany bez żadnych modyfikacji
     * @param bool $uploadedByForm flaga określająca czy plik został wgrany poprzez formularz
     * @param string|null $filename nazwa pliku pod jaką ma zostać zapisany plik
     * @param string|null $fileExtension rozszerzenie zapisanego pliku
     * 
     * @return string
     */
    public static function saveFile(string $filePath, string $folder, bool $originalSource, bool $uploadedByForm, ?string $filename = null, ?string $fileExtension = null): string {

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
        }

        if ($filename === null) {
            $encrypter = new Encrypter;
            $filename = $encrypter->generateToken(64, Image::class, 'filename', $fileExtension);
        }

        $fileContents = file_get_contents($filePath);
        $fileDestination = $folder . '/' . $filename;

        if ($originalSource) {
            Storage::put($fileDestination, $fileContents);
        } else {
            switch ($fileExtension) {
                case 'jpeg':
                    $uploadedImage = imagecreatefromstring($fileContents);
                    $imageWidth = imagesx($uploadedImage);
                    $imageHeight = imagesy($uploadedImage);
                    $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
                    imagecopyresampled($newImage, $uploadedImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);
                    imagejpeg($newImage, 'storage/' . $fileDestination, 100); // TODO Potestować ile maksymalnie można zmniejszyć jakość obrazu, żeby nadal był akceptowalny
                    break;
            }
        }

        return $filename;
    }

    /**
     * Proces zapisania zdjęcia profilowego na serwerze
     * 
     * @param string $avatarPath ścieżka do zdjęcia które ma zostać zapisane
     * @param bool $uploadedByForm flaga określająca czy plik został wgrany poprzez formularz
     * 
     * @return string
     */
    public static function saveAvatar(string $avatarPath, bool $uploadedByForm): string {
        return self::saveFile($avatarPath, 'avatars', false, $uploadedByForm, null, 'jpeg');
    }
}
