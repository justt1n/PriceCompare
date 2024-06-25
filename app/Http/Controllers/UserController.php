<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Http\Helpers\NotificationHelper;
class UserController extends Controller
{
    protected $userService;
    public function __construct(){
        $this->userService = new UserService();
    }
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function notification($message, $alertType)
    {
        Session::flash('message', $message);
        Session::flash('alert-type', $alertType);
    }
    public function store()
    {
        // $data = request()->validate([
        //     'name' => 'required',
        //     'email' => 'required',
        //     'password' => 'required',
        //     'role' => 'required',
        // ]);
        // $userEmail = User::where('email', request()->email)->get();
        // try {
        //     if ($userEmail->isEmpty()){
        //         User::create($data);
        //         NotificationHelper::successNotification('Add Successful');
        //     }else{
        //         NotificationHelper::errorNotification('Add User Error! Please check again');
        //     }
        // } catch (\Exception $e) {
        //     \Log::error($e->getMessage());
        // }
        $this->userService->store();
        return redirect()->route('admin.view.list');
    }

    public function deleteById($id)
    {
        $userID = Auth::id();
        if ($userID == $id) {
            return redirect()->back();
        }
        $this->userService->delete($id);
        return redirect()->route('admin.view.list');
    }

    public function updateRoleById(Request $request, $id)
    {
        $userID = Auth::id();
        if ($userID == $id) {
            return redirect()->back();
        }
        $this->userService->updateRoleById($id);
        return redirect()->back();
    }
}