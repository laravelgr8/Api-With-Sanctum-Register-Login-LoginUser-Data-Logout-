<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function sendResponse($result,$message)
    {
    	$response=[
    		"Status"=>'true',
    		"data"  =>$result,
    		"message"=>$message
    	];
    	return response()->json($response,200);
    }

    public function sendError($error,$message=[],$code = 404)
    {
    	$response=[
    		"Status"=>'false',
    		"message"=>$error
    	];
    	if(!empty($message))
    	{
    		$response["data"]=$message;
    	}
    	return response()->json($response,$code);
    }

    public function register(Request $request)
    {
    	$validator=Validator::make($request->all(),[
    		"name"=>'required',
    		"email"=>'required',
    		"password"=>'required'
    	]);
    	if($validator->fails())
    	{
    		return $this->sendError($validator->errors(),'Validation Error');
    	}

    	$input=$request->all();
    	$input["password"]=Hash::make($request->password);
    	$user=User::create($input);
    	$success["token"]=$user->createToken('MyApp')->plainTextToken;
    	$success['name']=$user->name;
    	return $this->sendResponse($success,"User Create");
    }


    public function login(Request $request)
    {
    	$validator=Validator::make($request->all(),[
    		"email"=>'required',
    		"password"=>'required'
    	]);
    	if($validator->fails())
    	{
    		return $this->sendError($validator->errors(),'Validation Error');
    	}

    	$user=User::where('email',$request->email)->first();
    	if($user && Hash::check($request->password,$user->password))
    	{
    		$success["token"]=$user->createToken('MyApp')->plainTextToken;
    		$success["name"]=$user->name;
    		return $this->sendResponse($success,"User logined");
    	}
    	else
    	{
    		return $this->sendError("Authenticate Error");
    	}
    }


    public function loginUser()
    {
    	$data=auth()->user();
    	return $this->sendResponse($data,"Login User Data");
    }

    public function logout()
    {
    	
    	Auth::user()->tokens()->delete();
    	//$request->user()->currentAccessToken()->delete();
    	return 'Logout';
    }
}
