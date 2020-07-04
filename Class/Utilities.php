<?php

class Utilities
{
    /**
     * @param string $folder_path
     * @return void
     */
    public static function emptyDirectory(string $folder_path): void
    {
        $files = glob($folder_path . '*');
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file);
        }
    }


    /**
     * @param int $statusCode
     * @param string $message
     */
    public static function deliverResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        $responseData['statusCode'] = $statusCode;
        $responseData['status'] = $message;
        echo json_encode($responseData);
        die;
    }
}