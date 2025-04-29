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
        //find specific student
        $student = Student::find($id);
        //if not exist
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        //if exist return with response 200
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ], 200);
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
        // Find the student
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
    
        // Validate input
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:students,email,'.$id,
            'age' => 'nullable|integer|min:1',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8',
        ]);
    
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('images', 'public');
        }
    
        // Update student record
        $student->update($validatedData);
    
        return response()->json([
            'data' => $student,
            'message' => 'Student updated successfully',
            'status' => 200
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //delete
        $student = Student::find($id);
        //if not exist
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        //if exist return with response 200
        $student->delete();
        return response()->json([
            'message' => 'Student deleted successfully',
            'status' => 200
        ], 200);

    }
//pagination
public function paginate(Request $request)
{
    $perPage = $request->input('per_page', 10);
    $students = Student::paginate($perPage);

    return response()->json([
        'data' => $students,
        'message' => 'Students retrieved successfully',
        'status' => 200
    ]);
}

    //search
    public function search(Request $request)
    {
        $query = trim(strtolower($request->input('query', '')));
    
        $students = Student::whereRaw('LOWER(name) LIKE ?', ["%$query%"])
            ->orWhereRaw('LOWER(email) LIKE ?', ["%$query%"])
            ->get();
    
        if ($students->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
    
        return response()->json([
            'data' => $students,
            'message' => 'Students retrieved successfully',
            'status' => 200
        ]);
    }
    
    //sort
    public function sort(Request $request)
    {
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
    
        $students = Student::orderBy($sortBy, $sortOrder)->get();
    
        return response()->json([
            'data' => $students,
            'message' => 'Students retrieved successfully',
            'status' => 200
        ]);
    }
    
    //filter
    public function searchById(Request $request)
    {
        $id = $request->input('id');
    
        if (!$id) {
            return response()->json(['error' => 'ID is required'], 400);
        }
    
        $student = Student::find($id);
    
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
    
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ]);
    }
    
  
  
    //search by name
    public function searchByName(Request $request)
    {
        $name = trim(strtolower($request->input('name', '')));
    
        if (empty($name)) {
            return response()->json(['error' => 'Name is required'], 400);
        }
    
        $students = Student::whereRaw('LOWER(name) LIKE ?', ["%$name%"])->get();
    
        if ($students->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
    
        return response()->json([
            'data' => $students,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ]);
    }
    
    //search by email
    public function searchByEmail(Request $request)
    {
        $email = $request->input('email');
        $student = Student::where('email', 'LIKE', "%$email%")->get();
        if ($student->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ], 200);
    }
    //search by phone
    public function searchByPhone(Request $request)
    {
        $phone = $request->input('phone');
        $student = Student::where('phone', 'LIKE', "%$phone%")->get();
        if ($student->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ], 200);
    }
    //search by address
    public function searchByAddress(Request $request)
    {
        $address = $request->input('address');
        $student = Student::where('address', 'LIKE', "%$address%")->get();
        if ($student->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ], 200);
    }
    //search by age
    public function searchByAge(Request $request)
    {
        $age = $request->input('age');
        $student = Student::where('age', 'LIKE', "%$age%")->get();
        if ($student->isEmpty()) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json([
            'data' => $student,
            'message' => 'Student retrieved successfully',
            'status' => 200
        ], 200);
    }
  



}
