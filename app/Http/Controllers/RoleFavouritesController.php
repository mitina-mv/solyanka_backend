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
        ])->get()->pluck('role_id')->all();
        
        $roles = Role::whereIn('id', $rolesFavourites)
        ->get();

        return response()->json([
            $roles,
        ], Response::HTTP_OK);
    }

    public function create($id)
    {
        # code...
    }

    public function destroy($id)
    {
        # code...
    }
}
