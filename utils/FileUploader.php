<?php

class FileUploader
{
    private static $image_ext = 'webp';

    private static function generateFileName($data)
    {
        return md5(date('YmdHis') . $data);
    }

    private static function getNameFromFile($filename)
    {
        return pathinfo($filename, PATHINFO_FILENAME);
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

    public static function upsertImageByDataUrl($directory, $name, $dataURL)
    {
        if (empty($dataURL)) throw new Exception('No se ha enviado el parámetro `data_url`');
        $finalName = empty($name) ? self::generateFileName('gen_file') : $name;

        $encodedData = str_replace(' ', '+', $dataURL);
        $realImageContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $encodedData));

        $imagePath = $directory . $finalName . '.' . self::$image_ext;

        $isCreated = file_put_contents($imagePath, $realImageContent);
        if (!$isCreated) throw new Exception('Error while creating file');

        return array(
            'original_name' => $finalName,
            'name' => $finalName . '.' . self::$image_ext,
            'size' => filesize($imagePath),
            'date' => filemtime($imagePath),
            'image_size' => getimagesize($imagePath),
            'type' => mime_content_type($imagePath),
            'extension' => self::$image_ext,
            'url' => IMAGES_URL . $finalName . '.' . self::$image_ext,
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

    public static function uploadWebP($directory, $file)
    {
        $isCreateWithName = isset($_GET['withName']);
        $ext = 'webp';
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
