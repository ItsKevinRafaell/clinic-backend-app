<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorStoreRequest;
use App\Http\Requests\DoctorUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(){
        $doctors = User::where('role', 'doctor')->with('clinic', 'specialization')->get();
        return response()->json([
            'status' => 'success',
            'data' => $doctors,
        ]);
    }

    public function store(DoctorStoreRequest $request){
        $request->validated();

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $doctor = User::create($data);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $filePath = $image->storeAs('doctor', $imageName, 'public');
            $doctor->image = '/storage/'.$filePath;
            $doctor->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ], 201);
    }

    public function update(DoctorUpdateRequest $request, $id){
        $request = new DoctorUpdateRequest($id);
        $request->validated();

        $data = $request->all();
        $doctor = User::find($id);
        $doctor->update($data);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $filePath = $image->storeAs('doctor', $imageName, 'public');
            $doctor->image = '/storage/'.$filePath;
            $doctor->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ]);
    }

    public function destroy($id){
        $doctor = User::find($id);
        $doctor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor deleted successfully',
        ]);
    }

    public function getDoctorActive(){
        $doctors = User::where('role', 'doctor')->where('status', 'active')->with('clinic', 'specialization')->get();
        return response()->json([
            'status' => 'success',
            'data' => $doctors,
        ]);
    }

    public function searchDoctor(Request $request){
        $doctor = User::where('role', 'doctor')
            ->where('name', 'like', '%'.$request->name.'%')
            ->where('specialist_id', $request->specialist_id)
            ->with('clinic', 'specialization')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ]);
    }

    public function getDoctorById($id){
        $doctor = User::where('role', 'doctor')->where('id', $id)->with('clinic', 'specialization')->first();
        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ]);
    }

    public function getDoctorBySpecialist($id){
        $doctor = User::where('role', 'doctor')->where('specialist_id', $id)->with('clinic', 'specialization')->get();
        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ]);
    }

    public function getDoctorByClinic($id){
        $doctor = User::where('role', 'doctor')->where('clinic_id', $id)->with('clinic', 'specialization')->get();
        return response()->json([
            'status' => 'success',
            'data' => $doctor,
        ]);
    }
}
