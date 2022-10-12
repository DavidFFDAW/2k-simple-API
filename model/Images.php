<?php

class Images {
    private $directory = '';
    private static $instance = null;

    public static function getInstance() {
        if (!isset(self::$instance)) {
                self::$instance = new User();
        }
        return self::$instance;
    }


    public function __construct() {
        $this->directory = MAIN_DIR . '../images/';
        $this->json = new ResponseJSON();
    }

    private function getDirectorySize($path){
        $bytestotal = 0;
        $path = realpath($path);
        if($path!==false && $path!='' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    private function formatFileOrDirSize($size) {
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

    public function getImages(Request $request) {
        $images = scandir($this->directory);
        $scanned = array_diff($images, array('.', '..'));
        $finalImages = array();
        
        foreach ($scanned as $file) {
            $dirFile = $this->directory . $file;
            if (!is_file($dirFile)) continue;

            $information = [];
            $information['url'] = IMAGES_URL.$file;
            $information['name'] = $file;
            $information['size'] = filesize($dirFile);
            $information['image_size'] = getimagesize($dirFile);
            $information['date'] = filemtime($dirFile);
            $information['type'] = mime_content_type($dirFile);
            $information['extension'] = pathinfo($dirFile, PATHINFO_EXTENSION);
            
            $finalImages['images'][] = $information;
        }

        $finalImages['directory_size'] = $this->formatFileOrDirSize($this->getDirectorySize($this->directory));

        return $this->json->setResponseAndReturn(200, 'Succesful', 'OK', $finalImages);
    }

    
    public function createImage(Request $request) {
        $finalResp = array();
        if (!isset($request->files) || empty($request->files))
            return ResponseJSON::error(400, 'Bad Request: No image(s) found');

        foreach ($request->files as $file) {
            $finalResp[] = FileUploader::uploadImage($this->directory, $file);
        }

        $lastError = error_get_last();

        if (isset($lastError) || !empty($lastError)) {
            return ResponseJSON::error(500, 'Internal Server Error: ' . $lastError['message']);
        }
        
        return $this->json->setResponseAndReturn(200, 'Succesful', 'OK', $finalResp);
    }


    public function updateImage (Request $req) {
        if (!isset($req->files) || empty($req->files))
            return ResponseJSON::error(400, 'Bad Request: No image(s) found');
        
        dd_json($req);
        $object = FileUploader::updateCurrentImage($this->directory, $req->files[0], $req->body->name);

        $lastError = error_get_last();

        if (isset($lastError) || !empty($lastError)) {
            return ResponseJSON::error(500, 'Internal Server Error: ' . $lastError['message']);
        }

        return $this->json->setResponseAndReturn(200, 'Succesful', 'OK', $object);
    }


    public function deleteImageByGET(Request $req) {
        $imageName = $req->params->img;

        if (!isset($imageName) || empty($imageName))
            return ResponseJSON::error(400, 'Bad Request: No image name found');

        $imagePath = $this->directory . $imageName;

        if (!file_exists($imagePath))
            return ResponseJSON::error(404, 'Not Found: Image not found');
        
        $isDeleted = unlink($imagePath);

        return $isDeleted 
            ? $this->json->setResponseAndReturn(200, 'Succesful', 'OK', array('message' => 'Image deleted'))
            : ResponseJSON::error(500, 'Internal Server Error: Image could not be deleted')
        ;
    }
}