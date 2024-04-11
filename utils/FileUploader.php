<?php

class FileUploader
{
    private static $image_ext = 'webp';

    private static function generateFileName($data)
    {
        return md5(date('YmdHis') . $data);
    }

    private static function getNameFromFile($file)
    {
        return pathinfo($file['name'], PATHINFO_FILENAME);
    }


    public static function updateCurrentImage($directory, $file, $filename)
    {
        $finalFilename = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (file_exists($filename)) {
            $isDeleted = unlink($filename);

            if (!$isDeleted) throw new Exception('Error deleting file');
        }

        $isMoved = move_uploaded_file($file['tmp_name'], $directory . $finalFilename . '.' . $ext);

        if (!$isMoved) {
            throw new Exception('Error while moving file');
        }

        return array(
            'original_name' => $file['name'],
            'name' => $finalFilename . '.' . $ext,
            'size' => $file['size'],
            'date' => filemtime($directory . $finalFilename . '.' . $ext),
            'image_size' => getimagesize($directory . $finalFilename . '.' . $ext),
            'type' => $file['type'],
            'extension' => $ext,
            'url' => IMAGES_URL . $finalFilename . '.' . $ext,
        );
    }


    public static function uploadImage($directory, $file)
    {
        $isCreateWithName = isset($_GET['withName']);
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $finalFilename = $isCreateWithName
            ? self::getNameFromFile($file['name'])
            : self::generateFileName($file['name']);

        $isMoved = move_uploaded_file($file['tmp_name'], $directory . $finalFilename . '.' . $ext);

        if (!$isMoved) {
            throw new Exception('Error while moving file');
        }

        return array(
            'original_name' => $file['name'],
            'name' => $finalFilename . '.' . $ext,
            'size' => $file['size'],
            'date' => filemtime($directory . $finalFilename . '.' . $ext),
            'image_size' => getimagesize($directory . $finalFilename . '.' . $ext),
            'type' => $file['type'],
            'extension' => $ext,
            'url' => IMAGES_URL . $finalFilename . '.' . $ext,
        );
    }
}
