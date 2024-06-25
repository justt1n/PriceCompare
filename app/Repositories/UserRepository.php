<?php
namespace App\Repositories;
use App\Models\User;
use App\Interfaces\IUserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


class UserRepository implements IUserRepository
{
    protected $user;
    public function __construct()
    {
        $this->user = new User();
    }
    public function save(array $user)
    {
        $data = [
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"]),
            "role" => $user["role"],
            "created_at" => Carbon::now(),
        ];

        return $this->user->create($data);
    }
    public function update(array $user, $id)
    {
        $data = [
            "username" => $user["username"],
            "email" => $user["email"],
            "password" => Hash::make($user["password"]),
            "role" => $user["role"],
        ];

        return $this->user::where("id", $id)->update($data);
    }
    public function delete($id)
    {
        return $this->user::where("id", $id)->delete();
    }
    public function getById($id)
    {
        return $this->user::where("id", $id)->first();
    }
    public function getAll()
    {
        return $this->user->all();
    }
    public function getByEmail($email)
    {
        return $this->user::where("email", $email)->get();
    }

    public function countAllAdmins()
    {
        return $this->user::where("role", "superAdmin")->count();
    }
    public function countAllUsers()
    {
        return $this->user::where("role", "admin")->count();
    }
}

?>
