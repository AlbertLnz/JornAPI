<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\DashBoard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class DashboardController extends Controller
{
  
    /**
     * Summary of __construct
     * @param \App\Services\Dashboard\DashboardService $dashboardService
     */
    public function __construct(private DashboardService $dashboardService){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse{
           
          $data=  $this->dashboardService->execute($request->user());

          return response()->json($data, 200);

    }
}
