<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response.
     *
     * @return \Illuminate\Http\Response
     */
    public function success($result=null, $message=null, $status='Ok')
    {
    	$response = [
            'successful' => true,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Request was successful':$message,
        ];
        return response()->json($response, 200);
    }

    /**
     * created response.
     *
     * @return \Illuminate\Http\Response
     */
    public function created($result=null, $message=null, $status='Created')
    {
    	$response = [
            'successful' => true,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Resource was created':$message,
        ];
        return response()->json($response, 201);
    }

    /**
     * unauthourised response.
     *
     * @return \Illuminate\Http\Response
     */
    public function bad_request($result=null, $message=null, $status='BadRequest')
    {
    	$response = [
            'successful' => false,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Bad request':$message,
        ];
        return response()->json($response, 400);
    }

    /**
     * unauthourised response.
     *
     * @return \Illuminate\Http\Response
     */
    public function unauthorised($result=null, $message=null, $status='Unauthorised')
    {
    	$response = [
            'successful' => false,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Unauthorised request':$message,
        ];
        return response()->json($response, 401);
    }

    /**
     * unauthourised response.
     *
     * @return \Illuminate\Http\Response
     */
    public function forbidden($result=null, $message=null, $status='Forbidden')
    {
    	$response = [
            'successful' => false,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Forbidden request':$message,
        ];
        return response()->json($response, 403);
    }

    /**
     * not found response.
     *
     * @return \Illuminate\Http\Response
     */
    public function not_found($result=null, $message=null, $status='NotFound')
    {
    	$response = [
            'successful' => false,
            'status' => $status,
            'data'    => $result,
            'message' => $message==null?'Resource was not found':$message,
        ];
        return response()->json($response, 404);
    }

    /**
     * server error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function server_error(\Throwable $th=null)
    {
    	$response = [
            'successful' => false,
            'status' => 'InternalServerError',
            'message' => ENV('SERVER_ERROR_MSG'),
        ];

        Log::error($th->getMessage());
        return response()->json($response, 500);
    }

    /**
     * error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function error($error, $data = [], $status='BadRequest', $code = 400)
    {
    	$response = [
            'successful' => false,
            'status' => $status,
            'message' => $error,
        ];

        if(!empty($data)){
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
