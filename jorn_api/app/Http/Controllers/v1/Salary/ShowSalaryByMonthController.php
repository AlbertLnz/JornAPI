<?php

namespace App\Http\Controllers\v1\Salary;

use App\Http\Controllers\Controller;
use App\Services\Salary\FindSalaryByMonthService;
use Illuminate\Http\Request;

class ShowSalaryByMonthController extends Controller
{
    public function __construct( private FindSalaryByMonthService $findSalaryByMonthService){}

    public function __invoke(Request $request)
    {
        $user = $request->user();
       $salary = $this->findSalaryByMonthService->execute(   $user->employee->id, $request->month, $request->year);
       return response()->json($salary, 200);
      
    }
}
