<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Str;
use Validator;
use File;
use DB;

class BaseController extends Controller
{
    //*************************  Index View  *********************************//
    public function index(Request $request)
    {
        return view('index');
    }

    //*************************  DataTable for Dynamic Data in table  *********************************//
    public function dataTable()
    {
        try {
            $data = Student::paginate(5);
            $returnHTML = view('table')->with('data', $data)->render();
            return response()->json(array('success' => 200, 'html' => $returnHTML));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //**************************  Step One View OR Get Data  ********************************//
    public function getStepOne(Request $request)
    {
        try {
            $token = $request->token;
            if (isset($token)) {
                $data = Student::where('token', '=', $request->token)->first();
                return view('step-one', compact(['data', 'token']));
            } else {
                return view('step-one');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //**************************  Step Two View OR Get Data  *********************************//
    public function getStepTwo(Request $request, $token)
    {
        try {
            $dropdown = Student::all();
            $data = Student::where('token', '=', $token)->first();
            return view('step-two', compact(['data', 'dropdown']));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //**************************  Step Three View OR Get Data  ********************************//
    public function getStepThree($token)
    {
        try {
            $data = Student::where('token', '=', $token)->first();
            return view('step-three', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //***************************  Step Four View OR Get Data  ********************************//
    public function getStepFour($token)
    {
        try {
            $data = Student::where('token', '=', $token)->first();
            return view('step-four', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //***************************  Step One Create & Update  *********************************//
    public function saveStepOne(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'steps' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            } else {
                if (isset($request->token)) {
                    $update = Student::where('id', '=', $request->id)->first();
                    $update->name = $request->input('name');
                    $update->email = $request->input('email');
                    $update->steps = $request->input('steps');
                    $update->token = $request->input('token');
                    $update->save();
                    $students = $update->token;
                    return redirect()->route('getStepTwo', $students);
                } else {
                    $token = Str::random(64);
                    $student = new Student;
                    $student->name = $request->input('name');
                    $student->email = $request->input('email');
                    $student->steps = $request->input('steps');
                    $student->token = $token;
                    $student->save();
                    $students =  $student->token;
                    return redirect()->route('getStepTwo', $students);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }
    //*************************  Step Two Create & Update  ********************************//
    public function saveStepTwo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'marks_obtained' => 'required',
                'passing_year' => 'required',
                'steps' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            } else {
                $update = Student::where('id', '=', $request->id)->first();
                $update->marks_obtained = $request->input('marks_obtained');
                $update->passing_year = $request->input('passing_year');
                $update->steps = $request->input('steps');
                $update->save();
                $student = $update->token;
                return redirect()->route('getStepThree', $student);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }
    //*****************************  Step Three Create & Update  *************************************//
    public function saveStepThree(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'contact' => 'required',
                'steps' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'error' => $validator->messages(),
                ]);
            } else {
                $update_data = Student::where('id', '=', $request->id)->first();
                $update_data->first_name = $request->input('first_name');
                $update_data->last_name = $request->input('last_name');
                $update_data->contact = $request->input('contact');
                $update_data->steps = $request->input('steps');
                $update_data->save();
                $student_token = $update_data->token;
                return redirect()->route('getStepFour', $student_token);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //**********************************  Delete Data  **************************************//
    public function deleteData($token)
    {
        try {
            $student = Student::where('token', '=', $token)->delete();
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //**************************  Change Status in DataTable  *******************************//
    public function changeStatus(Request $request)
    {
        try {
            $users_status = Student::find($request->get('id'));
            if ($users_status->status == 0) {
                $users_status->status = 1;
                $users_status->save();
            } else {
                $users_status->status = 0;
                $users_status->save();
            }
            return response()->json(['status' => 200, 'success' => 'Status change successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //***************************  Update Year in DataTable  *********************************//
    public function updateYear(Request $request)
    {
        try {
            $student = Student::find($request->id);
            $student->passing_year = $request->passing_year;
            $student->save();
            return response()->json(['status' => 200, 'success' => 'Passing year change successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //***************************  Edit Page As Per Steps  *********************************//
    public function editRoute($token)
    {
        try {
            $data = Student::where('token', '=', $token)->first();
            $token_data = $data->token;
            if ($data->steps == 1) {
                return redirect()->route('getStepTwo', $token_data);
            }
            if ($data->steps == 2) {
                return redirect()->route('getStepThree', $token_data);
            }
            if ($data->steps == 3) {
                return redirect()->route('StepOne', $token_data);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //*********************************  Search Data  ******************************//
    public function search(Request $request)
    {
        try {
                if ($request->ajax()) {
                    if ($request->get('status') == 1) {
                        $data = DB::table('students')
                        ->where('status', '=', $request->status)
                        ->orWhere('name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('email', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('marks_obtained', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('passing_year', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('first_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('last_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('contact', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('image', 'LIKE', '%' . $request->search . "%")->paginate($request->record);
                    } elseif ($request->get('status') == 0) {
                        $data = DB::table('students')
                        ->where('status', '=', $request->status)
                        ->orWhere('name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('email', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('marks_obtained', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('passing_year', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('first_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('last_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('contact', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('image', 'LIKE', '%' . $request->search . "%")->paginate($request->record);
                    } else {
                        $data = DB::table('students')
                        ->where('status', '=', $request->status)
                        ->where('name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('email', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('marks_obtained', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('passing_year', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('first_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('last_name', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('contact', 'LIKE', '%' . $request->search . "%")
                        ->orWhere('image', 'LIKE', '%' . $request->search . "%")->paginate($request->record);
                    }
                
                
                if ($data) {
                    $returnHTML = view('table')->with('data', $data)->render();
                    return response()->json(array('success' => 200, 'html' => $returnHTML));
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //*********************************  Data as Per Status(active or inactive)  ***********************************//
    public function status(Request $request)
    { 
        try {
            $data = null;
            if (isset($request->status)) {
                $data = Student::where('status', '=', $request->status)->paginate(5);
            }
            $returnHTML = view('table')->with('data', $data)->render();
            return response()->json(array('success' => 200, 'html' => $returnHTML));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }
    //*********************************  Multiple Data Status Active  ***********************************//
    public function activeAll(Request $request)
    {
        try {
            $id = $request->id;
            $value = ['status' => 1];
            $student = Student::whereIn('id',$id)->where('status','=',0)->update($value);
            return response()->json(['status' => 200, 'success' => 'Status change successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //*********************************  Multiple Data Status InActive  ***********************************//
    public function inactiveAll(Request $request)
    {
        try {
            $id = $request->id;
            $value = ['status' => 0];
            $student = Student::whereIn('id',$id)->where('status','=',1)->update($value);
            return response()->json(['status' => 200, 'success' => 'Status change successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Fail !! Something Went Wrong !!');
            throw $e;
        }
    }

    //*********************************  Image Crop and Save  ***********************************//
    public function image(Request $request)
    {
        try {
            $folderPath = public_path('image/');
            $image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = uniqid() . '.png';
            $imageFullPath = $folderPath.$imageName;
            file_put_contents($imageFullPath, $image_base64);
            $update_data = Student::where('id', '=', $request->id)->first();
            $update_data->image = $imageName;
            $update_data->save();
            return response()->json(['success'=>'success']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Insertion Fail !! Something Went Wrong !!');
            throw $e;
        }
    }
    //*********************************  Record Limit  ***********************************//
    public function recordLimit(Request $request){

        $perPage = $request->record;
        $data = Student::orderBy('id','asc')->paginate($perPage);

        $returnHTML = view('table')->with('data', $data)->render();
        return response()->json(array('success' => 200, 'html' => $returnHTML));
    }
}
