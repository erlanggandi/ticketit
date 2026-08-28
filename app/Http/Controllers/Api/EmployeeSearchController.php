<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $employees = Employee::query()
            ->where('is_active', true)
            ->whereRaw('lower(employee_name) like ?', ['%'.strtolower($query).'%'])
            ->orderBy('employee_name')
            ->limit(8)
            ->get(['id', 'employee_name', 'department']);

        return response()->json([
            'data' => $employees->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->employee_name,
                'department' => $e->department,
            ]),
        ]);
    }
}
