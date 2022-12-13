<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreManyRequest;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\School;
use App\Models\User;
use App\Queries\EmployeeQuery;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Employee::class);
        return response((new EmployeeQuery)->includes()->filterSortPaginate());
    }

    public function store(EmployeeStoreRequest $request)
    {
        $user = User::create($request->only('email', 'name') + ['password' => $request->nip]);
        $user->employee()->create($request->validated());
        $user->employee->schools()->attach(School::whereIn('id', $request->school_ids)->get());
        return response($user);
    }

    public function storeMany(EmployeeStoreManyRequest $request)
    {
        $users = $request->only('name', 'email', 'nip');
        $users = collect($users)->map(fn ($u) => collect($u)->put('password', $u['nip'])->toArray());
        $employee = $request->only('nip', 'classroom_id');

        DB::beginTransaction();
        User::insert($users);

        $users = User::whereIn('email', $request->email)
            ->orderByRaw('FIELD(email,' . collect($request->email)->implode(fn ($e) => "'$e'", ',') . ')')
            ->get();
        for ($i = 0; $i < sizeof($users); $i++) {
            $users[$i]->student = $employee[$i] + ['user_id' => $users[$i]['id'], 'created_at' => now(), 'updated_at' => now()];
        }
        Employee::insert($users->pluck('employee')->toArray());
        DB::commit();
        return response(['message' => 'ok']);
    }


    public function show(Employee $employee)
    {
        $this->authorize('view', $employee);
        return response($employee);
    }

    public function showByNip($nip)
    {
        return response((new EmployeeQuery)->where('nip', $nip)->includes()->firstOrFail());
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        DB::beginTransaction();
        if ($request->name) $employee->user()->update($request->only('name'));
        if ($request->school_ids) {
            $employee->schools()->detach();
            $employee->schools()->attach($request->school_ids);
            // School::whereIn('id', $request->school_ids)->get()
        }
        $employee->update($request->validated());
        DB::commit();
        return response($employee);
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete', $employee);
        $employee->delete();
        return response(['message' => 'deleted']);
    }
}
