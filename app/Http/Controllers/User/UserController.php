<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show()
    {
        return new UserResource(auth()->user());
    }


    public function update(UpdateRequest $request, User $user)
    {
        $credentials = $request;

        if (isset($credentials['files'])) {

            foreach ($credentials['files'] as $file) {
                $extension = $file->getClientOriginalExtension();
                $path = 'photo/users/user_' . auth()->user()->id;
                $fileName = md5(microtime()) . '.' . $extension;

                if (auth()->user()->information->photo_path != 'storage/photo/1.jpg') {
                    Storage::disk('public')->delete(mb_substr(auth()->user()->information->photo_path, 8));
                }

                $sendedFile = $file->storeAs('public/' . $path, $fileName);

                auth()->user()->information->update([
                    'photo_path' => 'storage/' . pathinfo(mb_substr($sendedFile, 7), PATHINFO_DIRNAME) . '/' . $fileName
                ]);

                return response(['data' => [
                    'photo_path' => auth()->user()->information->photo_path
                ]]);
            }
        }

        if (isset($credentials['name'])) {
            $user->update([
                'name' => $credentials['name']
            ]);
        }

        if (isset($credentials['surname'])) {
            $user->update([
                'surname' => $credentials['surname']
            ]);
        }

        if (isset($credentials['bio'])) {
            $user->information->update([
                'bio' => $credentials['bio']
            ]);
        }

        if (isset($credentials['password'])) {
            $credentials['password'] = bcrypt($credentials['password']);

            $user->update([
                'password' => $credentials['password']
            ]);
        }

        return response([]);
    }


    public function destroy(User $user)
    {
        //
    }
}
