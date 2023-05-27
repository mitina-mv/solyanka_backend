<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleFavourites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleFavouritesController extends Controller
{
    public function list()
    {
        // $user = Auth::user();
        dd($user = auth('middleware')->user());
        if(!$user)
        {
            return response()->json([
                'status' => 'error',
                'message' => "Вы не авторизованы!"
            ], Response::HTTP_BAD_REQUEST);
        }

        $rolesFavourites = RoleFavourites::where([
            'user_id' => $user->id
        ])->get()->pluck('role_id')->all();
        
        $roles = Role::whereIn('id', $rolesFavourites)->get();

        return response()->json([
            $roles,
        ], Response::HTTP_OK);
    }

    public function create($id)
    {
        if($role = Role::find($id)) 
        {
            $roleFavourite = RoleFavourites::where([
                'user_id' => Auth::user()->id,
                'role_id' => $role->id
            ])->first();

            if(!isset($roleFavourite))
            {
                $roleFavourite = RoleFavourites::create([
                    'user_id' => Auth::user()->id,
                    'role_id' => $role->id
                ]);

                return response()->json([
                    'message' => "Все хорошо"
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Эта роль уже добавлена в избранное"
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Роли с указанным ID не существует"
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        if($role = Role::find($id)) 
        {
            $roleFavourite = RoleFavourites::where([
                'user_id' => Auth::user()->id,
                'role_id' => $role->id
            ])->first();

            if(isset($roleFavourite))
            {
                $roleFavourite->delete();

                return response()->json([
                    'message' => "Все хорошо"
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Этой роли нет в избранном"
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Роли с указанным ID не существует"
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
