<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolStoreRequest;
use App\Http\Requests\SchoolUpdateRequest;
use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', School::class);
        return response(School::all());
    }


    public function store(SchoolStoreRequest $request)
    {
        $school = School::create($request->validated());
        return response($school);
    }


    public function show(School $school)
    {
        return response($school);
    }


    public function update(SchoolUpdateRequest $request, School $school)
    {
        $school->update($request->validated());
        return response($school);
    }

    public function destroy(School $school)
    {
        $this->authorize('delete', $school);
        $school->delete();
        return response(['message' => 'deleted']);
    }
}
