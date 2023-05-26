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
        $user = Auth::user();
        $rolesFavourites = RoleFavourites::where([
            'user_id' => $user->id
        ])->select('id')->pluck('id');

        $roles = Role::where([
            'user_id' => $user->id,
            'role_id' => $rolesFavourites
        ]);

        return response()->json([
            $roles,
        ], Response::HTTP_OK);
    }
}
