<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function __construct(private ActivityLogger $activityLogger)
    {
    }

    public function admin_profile()
    {
        $logs = ActivityLog::latest()->take(10)->get();
        $admins = DB::table('users')->get();

        return view('themes.admin_profile.admin_profile', compact('admins', 'logs'));
    }

    public function adminProfile(Request $request, $id)
    {
        $admin = DB::table('admin')->where('id', $id)->first();

        $updateData = [
            'email' => $request->email,
            'name' => $request->name,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $filename);
            $newPath = 'images/' . $filename;

            if ($admin->profile && $admin->profile !== 'dist/img/avatar.png') {
                $oldFilePath = public_path($admin->profile);
                if (File::exists($oldFilePath)) {
                    File::delete($oldFilePath);
                }
            }

            $updateData['profile'] = $newPath;
            session(['profile' => $newPath]);
        }

        DB::table('admin')->where('id', $id)->update($updateData);

        session(['name' => $request->name]);
        $userName = session('name');
        $this->activityLogger->log(
            'updated',
            "Updated Admin Profile: {$request->name} | Responsible: {$userName}"
        );

        session()->flash('save', 'Admin Info updated successfully.');
        return redirect()->back();
    }
}
