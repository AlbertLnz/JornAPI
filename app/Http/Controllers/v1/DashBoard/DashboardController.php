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
  

    public function __construct(private DashboardService $dashboardService){}
    public function __invoke(Request $request): JsonResponse{
           
          $data=  $this->dashboardService->execute($request->user());

          return response()->json($data, 200);

    }
}
