<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreManyRequest;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;
use App\Models\User;
use App\Queries\StudentQuery;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Student::class);
        return response((new StudentQuery)->includes()->filterSortPaginateWithAppend());
    }

    public function store(StudentStoreRequest $request)
    {
        DB::beginTransaction();
        $user = User::create($request->only('email', 'name') + ['password' => $request->nis]);
        $user->student()->create($request->only(['nis', 'classroom_id']));
        DB::commit();
        return response($user);
    }

    public function storeMany(StudentStoreManyRequest $request)
    {
        $users = $request->only('name', 'email', 'nis');
        $users = collect($users)->map(fn ($u) => collect($u)->put('password', $u['nis'])->toArray());
        $students = $request->only('nis', 'classroom_id');

        DB::beginTransaction();
        User::insert($users);

        $users = User::whereIn('email', $request->email)
            ->orderByRaw('FIELD(email,' . collect($request->email)->implode(fn ($e) => "'$e'", ',') . ')')
            ->get();
        for ($i = 0; $i < sizeof($users); $i++) {
            $users[$i]->student = $students[$i] + ['user_id' => $users[$i]['id'], 'created_at' => now(), 'updated_at' => now()];
        }
        Student::insert($users->pluck('student')->toArray());
        DB::commit();
        return response(['message' => 'ok']);
    }


    public function show(Student $student)
    {
        // $this->authorize('view', $student);
        return response($student);
    }

    public function showByNis($nis)
    {
        return response((new StudentQuery)->where('nis', $nis)->includes()->firstOrFail());
    }


    public function update(StudentUpdateRequest $request, Student $student)
    {
        if ($request->name || $request->email) $student->user()->update($request->except('nis', 'classroom_id'));
        $student->update($request->validated());
        return response($student);
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        $student->delete();
        return response(['message' => 'deleted']);
    }
}
