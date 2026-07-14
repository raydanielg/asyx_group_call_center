<?php

namespace App\Http\Controllers;

trait AjaxResponseTrait
{
    protected function ajaxSuccess(string $message = 'Action completed successfully.', string $redirect = null, array $extra = [])
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(array_merge([
                'success' => true,
                'message' => $message,
                'redirect' => $redirect,
            ], $extra));
        }

        return back()->with('success', $message);
    }

    protected function ajaxError(string $message = 'Something went wrong.', int $status = 400, array $extra = [])
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(array_merge([
                'success' => false,
                'message' => $message,
            ], $extra), $status);
        }

        return back()->with('error', $message);
    }
}
