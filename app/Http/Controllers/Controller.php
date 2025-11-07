<?php

namespace App\Http\Controllers;

abstract class Controller
{

    protected function successMessage($message, $additional = []){
        return response()->json(array_merge([
            'success' => true,
            'message' => $message
        ], $additional));
    }

    protected function errorMessage($message,$code = 400){
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}
