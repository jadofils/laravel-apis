<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //find all students'
        $students = Student::all();
        //if not exist
        if ($students->isEmpty()) {
            return response()->json(['error' => 'No students found'], 404);
        }
        //if exist return with response 200
        return response()->json([
            'data' => $students,
            'message' => 'Students retrieved successfully',
            'status' => 200
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'age' => 'required|integer|min:1',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048| nullable',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        // Create a new student
        $student = new Student();
        $student->name = $validatedData['name'];
        $student->email = $validatedData['email'];
        $student->age = $validatedData['age'];
        $student->phone = $validatedData['phone'];
        $student->address = $validatedData['address'];

        // Image Handling
        if ($request->hasFile('image')) {
            $student->image = $request->file('image')->store('images', 'public');
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        $student->password = bcrypt($validatedData['password']);
        $student->save();

        return response()->json(['message' => 'Student created successfully', 'data' => $student], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
    }
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
