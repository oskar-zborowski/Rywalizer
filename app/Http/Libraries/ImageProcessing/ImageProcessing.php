<?php

namespace App\Http\Libraries\ImageProcessing;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Models\User;

/**
 * Klasa umożliwiająca przetwarzanie obrazów
 */
class ImageProcessing
{
    /**
     * Proces zapisania na serwerze zdjęcia profilowego użytkownika
     * 
     * @param string $avatarPath ścieżka do zdjęcia profilowego
     * 
     * @return string
     */
    public static function saveAvatar(string $avatarPath): string {

        $encrypter = new Encrypter;
        $avatarFileExtension = '.' . env('AVATAR_FILE_EXTENSION');
        $avatarFilename = $encrypter->generateToken(64, User::class, 'avatar', $avatarFileExtension);

        $avatarContents = file_get_contents($avatarPath);
        $uploadedImage = imagecreatefromstring($avatarContents);
        $imageWidth = imagesx($uploadedImage);
        $imageHeight = imagesy($uploadedImage);
        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($newImage , $uploadedImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);

        $avatarDestination = 'storage/avatars/' . $avatarFilename;
        imagejpeg($newImage, $avatarDestination, 100); // TODO Potestować ile maksymalnie można zmniejszyć jakość obrazu, żeby nadal był akceptowalny

        return $avatarFilename;
    }
}
