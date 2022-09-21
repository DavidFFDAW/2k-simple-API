<?php

class FileUploader {
    private static $image_ext = 'webp';

    private static function generateFileName($data) {
        return md5(date('YmdHis') . $data);
    }
    
    public static function uploadImage($directory, $file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $finalFilename = self::generateFileName($file['name']);
        $isMoved = move_uploaded_file($file['tmp_name'], $directory . $finalFilename . '.' . $ext);

        if (!$isMoved) {
            throw new Exception('Error while moving file');
        }

        return array(
            'original_name' => $file['name'],
            'name' => $finalFilename.'.'.$ext,
            'size' => $file['size'],
            'date' => filemtime($directory . $finalFilename . '.' . $ext),
            'image_size' => getimagesize($directory . $finalFilename . '.' . $ext),
            'type' => $file['type'],
            'extension' => $ext,
            'url' => IMAGES_URL . $finalFilename . '.' . $ext,
        );
    }
}