<?php

namespace App\Http\Controllers;

abstract class Controller
{

    protected function successMessage($message, $data = [], $code = 200){
        return response()->json(array_merge([
            'success' => true,
            'message' => $message
        ], $data), $code);
    }

    protected function errorMessage($message,$code = 400){
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
