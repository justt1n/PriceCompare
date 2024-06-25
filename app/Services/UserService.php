<?php

namespace App\Services;
use App\Repositories\UserRepository;
use App\Http\Helpers\NotificationHelper;
class UserService
{
    private $userRepository;
    public function __construct(){
        $this->userRepository = new UserRepository();
    }
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        $userEmail = $this->userRepository->getByEmail(request()->email);
        try {
            if ($userEmail->isEmpty()) {
                $this->userRepository->save($data);
                NotificationHelper::successNotification('Add Successful');
            }else{
                NotificationHelper::errorNotification('Add User Error! Please check again');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

    }

    public function delete($id){
        try {
            $this->userRepository->delete($id);
            NotificationHelper::successNotification('Delete Successful');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    public function updateRoleById($id){
        try {
            //code...
            $user = $this->userRepository->getById($id);
            $user->role = request()->role;
            $user->save();
            NotificationHelper::successNotification('Changle Role Successful');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}

?>