<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserServices
{
    /**
     * Метод отвечает за обновление профиля пользователя
     *
     * @param UserRequest $request
     * @param User $user
     * @return User
     */
    public function updateUser(UserRequest $request, User $user): User
    {
        $params = $request->toArray();

        if (isset($params['password'])) {
            $user->password = Hash::make($params['password']);
        }

        if (isset($params['phone'])) {
            $user->email = $params['phone'];
        }

        if (isset($params['name'])) {
            $user->name = $params['name'];
        }

        if (isset($params['surname'])) {
            $user->name = $params['name'];
        }

        if ($request->hasFile('avatar_url')) {
            $newAvatar = $request->file('avatar_url');
            $oldAvatar = $user->avatar_url;
            if ($oldAvatar) {
                Storage::delete($oldAvatar);
            }
            $filename = $newAvatar->store('public/avatars', 'local');
            $user['avatar_url'] = $filename;
        }

        $user->update();

        return $user;
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $userId
     * @return void
     */
    public function deleteUser(int $userId): void
    {
        $comment = User::whereId($userId)->firstOrFail();
        $comment->delete();
    }
}
