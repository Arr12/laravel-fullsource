<?php
if (!function_exists('checkFileExtension')) {
    function checkFileExtension($file, $allowedExtensions)
    {
        // Get the file extension
        $fileExtension = strtolower($file->getClientOriginalExtension());

        // Check if the file extension is in the allowed extensions array
        return in_array($fileExtension, $allowedExtensions);
    }
}
