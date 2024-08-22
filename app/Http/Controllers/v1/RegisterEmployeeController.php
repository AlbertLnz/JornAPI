<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Services\RegisterEmployeeService;
use Illuminate\Http\Request;

class RegisterEmployeeController extends Controller
{

    public function __construct(private RegisterEmployeeService $service) {
        
    }
    public function __invoke(RegisterEmployeeRequest $request)
    {
        $this->service->execute($request->name,
                                 $request->email, 
                                 $request->password, 
                                 $request->normal_hourly_rate, 
                                 $request->overtime_hourly_rate,
                                 $request->night_hourly_rate??0.00, 
                                 $request->holiday_hourly_rate??0.00, 
                                 $request->irpf??0.00);

        return response()->json(['message' => 'Employee created successfully'], 201);
    }

    public function holamundo() {
        return 'Hola mundo';
    }
}
