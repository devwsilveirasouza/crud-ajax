<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.index');
    }

    public function fetchStudent()
    {
        $students = Student::all();
        return response()->json([
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:191',
            'email'         => 'required|email|max:191',
            'phone'         => 'required|max:191',
            'course'        => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'errors'    => $validator->messages(),
            ]);
        } else {
            $student = new Student;
            $student->name          = $request->input('name');
            $student->email         = $request->input('email');
            $student->phone         = $request->input('phone');
            $student->course        = $request->input('course');
            $student->save();
            return response()->json([
                'status'    => 200,
                'message'   => 'Student Added Successfully!',
            ]);
        }
        //$student = new Student;
    }
    public function edit($id)
    {
        $student = Student::find($id);
        if ($student) //Se tiver tudo ok retorna abaixo
        {
            return response()->json([
                'status'    => 200,
                'student'   => $student,
            ]);
        } else //Se não for encontrado o parametro retorna mensagem abaixo
        {
            return response()->json([
                'status'    => 404,
                'message'   => 'Student Not Found',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:191',
            'email'         => 'required|email|max:191',
            'phone'         => 'required|max:191',
            'course'        => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'errors'    => $validator->messages(),
            ]);
        } else {
            $student = Student::find($id); // alteração

            if ($student) //Se tiver tudo ok retorna abaixo
            {
                $student->name          = $request->input('name');
                $student->email         = $request->input('email');
                $student->phone         = $request->input('phone');
                $student->course        = $request->input('course');
                $student->update(); // alteração

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Student Updated Successfully!',
                ]);
                return response()->json([
                    'status'    => 200,
                    'student'   => $student,
                ]);
            }
            else //Se não for encontrado os parametros retorna mensagem abaixo
            {
                return response()->json([
                    'status'    => 404,
                    'message'   => 'Student Not Found',
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        $student->delete();

        return response()->json([
            'status'    => 200,
            'message'   => 'Student Deleted Successfully!',
        ]);
    }
}
