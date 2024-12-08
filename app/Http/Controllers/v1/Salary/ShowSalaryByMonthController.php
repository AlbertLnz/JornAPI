<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Salary;

use App\Exceptions\SalaryNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\Salary\FindSalaryByMonthService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ShowSalaryByMonthController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Salary\FindSalaryByMonthService $findSalaryByMonthService
     */
    public function __construct( private FindSalaryByMonthService $findSalaryByMonthService){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        try{
            $employee = $request->user()->employee;
            $salary = $this->findSalaryByMonthService->execute(   $employee->id, $request->month, $request->year);
            return response()->json(['salary' => $salary], 200);
        }catch(SalaryNotFoundException $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
    
      
    }
}
