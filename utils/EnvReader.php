<?php
class EnvReader {
      private static $instance = null;

      private $environmentVars = [];

      public static function getInstance($path = null) {
            if (!isset(self::$instance)) {
                  self::$instance = new EnvReader($path);
            }
            return self::$instance;
      }

      private function __construct(?string $path = null) {
            $this->environmentVars = $this->loadENV($path);            
      }

      private function loadENV (?string $path = null) {
            $realPath = (isset($path) && !empty($path)) ? $path : dirname(__FILE__) . DIRECTORY_SEPARATOR;
            $finalAssociativeArray = array();

            $envFileContent = file_get_contents($realPath . '.env');
            $vars = explode("\n", $envFileContent);

            $filteredEmptyVars = array_filter($vars, function ($var) {
                return !isset($var) || !empty($var);
            });

            foreach ($filteredEmptyVars as $var) {
                  $exploded = explode("=", $var);
                  $finalAssociativeArray[trim($exploded[0])] = trim($exploded[1]);
            }

            return $finalAssociativeArray;
      }

      public function get(?string $key = null) {
            if (!isset($key) || empty($key)) {
                  return $this->environmentVars;
            }
            return $this->environmentVars[$key] ?? false;
      }
}