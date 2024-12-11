<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\Services\Auth\LogOutService;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
/**
 * Summary of LogOutController
 */
class LogOutController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Auth\LogOutService $logOutService
     */
    public function __construct(private LogOutService $logOutService){}
    
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\InvalidTokenException
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request):JsonResponse
    {

        try{
                $this->logOutService->logOut( $request->bearerToken());

                return response()->json(['message' => 'Logged out successfully']);

        }catch(InvalidTokenException $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
      
 
    }
}
