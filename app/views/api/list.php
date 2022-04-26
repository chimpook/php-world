<?php 
/**
 * Default API listing template
 */
require APPROOT . '/views/api/inc/header.php';
echo json_encode($data);
