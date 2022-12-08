<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassroomStoreManyRequest;
use App\Http\Requests\ClassroomStoreRequest;
use App\Http\Requests\ClassroomUpdateRequest;
use App\Models\Classroom;
use App\Queries\ClassroomQuery;

class ClassroomController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Classroom::class);
        return response((new ClassroomQuery)->includes()->filterSortPaginateWithAppend());
    }


    public function store(ClassroomStoreRequest $request)
    {
        $classroom = Classroom::create($request->validated());
        return response($classroom);
    }

    public function storeMany(ClassroomStoreManyRequest $request)
    {
        Classroom::insert($request->validated());
        return response(['message' => 'ok']);
    }


    public function show(Classroom $classroom)
    {
        $this->authorize('view', $classroom);
        return response($classroom);
    }


    public function update(ClassroomUpdateRequest $request, Classroom $classroom)
    {
        $classroom->update($request->validated());
        return response($classroom);
    }

    public function destroy(Classroom $classroom)
    {
        $this->authorize('delete', $classroom);
        $classroom->delete();
        return response(['message' => 'deleted']);
    }
}
