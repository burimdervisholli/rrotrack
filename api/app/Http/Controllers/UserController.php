<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'avatar'    => 'sometimes|dimensions:max_width=200,max_height=200'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $avatarFileName = '';
        if($request['avatar'] != null)
        {
            $file = $request['avatar'];
            $avatarFileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            
            $file->move($destinationPath, $avatarFileName);
        }
    
        $coverFileName = '';
        if($request['cover'] != null)
        {
            $file = $request['cover'];
            $coverFileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');

            $file->move($destinationPath, $coverFileName);
        }

        $user = User::create([
            'name'      => $request['name'],
            'email'     => $request['email'],
            'password'  => Hash::make($request['password']),
            'bio'       => $request['bio'],
            'avatar'    => $avatarFileName,
            'cover'     => $coverFileName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
        ], 200);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) 
        {
            if (Hash::check($request->password, $user->password)) 
            {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                return response()->json([
                    'success'   => true,
                    'token'     => $token,
                    'user'      => $user,
                ], 200);
            } 
            else 
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Password missmatch',
                ], 422);
            }
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'message' => 'User does not exist',
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'success'   => true,
            'message'   => 'You have been succesfully logged out!',
        ], 200);
    }

    public function update(Request $request, $userId)
    {
        $requestUser = $request->user();
        $user = User::findOrFail($userId);       

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        if($requestUser->id == $user->id || $requestUser->hasRole('administrator'))
        {
            if($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 422);
            }
            
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->bio = $request['bio'];
            $user->save();

            return response()->json([
                'success'   => true,
                'user'      => $user,
                'message'   => 'Update successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthorized',
            ], 401);
        }
    }

    public function updatePassword(Request $request, $userId)
    {
        $requestUser = $request->user();
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|different:old_password',
        ]);

        if($requestUser->id == $user->id || $requestUser->hasPermissionTo('edit user', 'administrator'))
        {
            if (!Hash::check($request['old_password'], $user->password))
            {
                return response()->json([
                    'success' => false,
                    'errors' => 'Password does not match!'
                ], 422);
            }

            if($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $user->password = Hash::make($request['new_password']);
            $user->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Password changed successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthorized',
            ], 401);
        }
    }

    public function updateAvatar(Request $request, $userId)
    {
        $requestUser = $request->user();
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'avatar'    => 'required|dimensions:max_width=200,max_height=200'
        ]);

        if($requestUser->id == $user->id || $requestUser->hasPermissionTo('edit user', 'administrator'))
        {
            if($validator->fails())
            {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $avatarFileName = '';
            if($request['avatar'] != null)
            {
                $file = $request['avatar'];
                $avatarFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/');
                
                $file->move($destinationPath, $avatarFileName);
                if(file_exists(public_path() . 'uploads/' . $user->avatar))
                {
                    File::delete($user->avatar);
                }
            }
            $user->avatar = $avatarFileName;
            $user->save();

            return response()->json([
                'success'   => true,
                'avatar'    => url('/') . '/uploads/' . $user->avatar,
                'message'   => 'Profile picture changed successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthorized',
            ], 401);
        }
    }

    public function updateCover(Request $request, $userId)
    {
        $requestUser = $request->user();
        $user = User::findOrFail($userId);

        if($requestUser->id == $user->id || $requestUser->hasPermissionTo('edit user', 'administrator'))
        {
            $coverFileName = '';
            if($request['cover'] != null)
            {
                $file = $request['cover'];
                $coverFileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/');

                $file->move($destinationPath, $coverFileName);
                if(file_exists(public_path() . 'uploads/' . $user->cover))
                {
                    File::delete($user->cover);
                }
            }
            $user->cover = $coverFileName;
            $user->save();

            return response()->json([
                'success'   => true,
                'cover'     => url('/') . '/uploads/' . $user->cover,
                'message'   => 'Cover changed successfully',
            ], 200);
        }
        else
        {
            return response()->json([
                'success'   => false,
                'message'   => 'Unauthorized',
            ], 401);
        }
    }

    public function assignRole(Request $request)
    {
        $user = User::findOrFail($request['user_id']);
        $user->assignRole($request['role']);

        return response()->json([
            'success'   => true,
            'message'   => 'Role assigned successfully!',
        ], 200);
    }

    public function givePermission(Request $request)
    {
        $user = User::findOrFail($request['user_id']);
        $user->givePermissionTo($request['permission']);

        return response()->json([
            'success'   => true,
            'message'   => 'Permission added successfully!',
        ], 200);
    }

}
