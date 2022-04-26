<?php

/**
 * Class ApiController
 */
 class ApiController
 {
     
     /**
      * @param string $model
      * 
      * @return object
      */
     public function model($model)
     {
         require_once '../app/models/' . $model . '.php';
         return new $model();
     }


     /**
      * Provides JSON response
      *
      * @param mixed $template
      * @param array $data
      * 
      * @return void
      */
     public function response($template, $data = [])
     {
         if (file_exists('../app/views/api/' . $template . '.php')) {
             require_once '../app/views/api/' . $template . '.php';
         } else {
             die('Api template does not exist');
         }
     }

     /**
      * Standard mode url getter
      *
      * @return string
      */
     public function getStdUrl()
     {
         return URLROOT . str_replace('/api/', '', $_SERVER['REQUEST_URI']);
     }

     /**
      * API mode url getter
      *
      * @return string
      */
     public function getApiUrl()
     {
        // TODO: clean this crutch
        return URLROOT . str_replace('/api/', 'api/', $_SERVER['REQUEST_URI']);
     }

 }
