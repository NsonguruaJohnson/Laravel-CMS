<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Users\UpdateProfileRequest;

class UsersController extends Controller
{
    public function index() {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    public function makeAdmin(User $user) {
        $user->role = 'admin';
        $user->save();

        return back()->with('success', 'User made admin successfully');
    }

    public function edit() {
        return view('users.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(UpdateProfileRequest $request) {
        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'about' => $request->about,
        ]);

        session()->flash('success', 'User profile updated successfully');

        return redirect()->back();
    }
}
