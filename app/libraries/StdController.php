<?php

/**
 * Base controller
 */
 class StdController
 {
     /**
      * @param mixed $model
      * 
      * @return object
      */
     public function model($model)
     {
         require_once '../app/models/' . $model . '.php';
         return new $model();
     }

     /**
      * Output data to screen
      *
      * @param mixed $view
      * @param array $data
      * 
      * @return void
      */
     public function view($view, $data = [])
     {
         if (file_exists('../app/views/std/' . $view . '.php')) {
             require_once '../app/views/std/' . $view . '.php';
         } else {
             die('View does not exist');
         }
     }

     /**
      * @return string
      */
     public function getApiUrl()
     {
         return URLROOT . 'api' . $_SERVER['REQUEST_URI'];
     }
 }
