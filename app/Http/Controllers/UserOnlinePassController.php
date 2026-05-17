<?php

namespace App\Http\Controllers;

use App\Models\UserOnlinePass;
use Illuminate\Http\Request;

class UserOnlinePassController extends Controller
{
    public function index()
    {
        $passes = UserOnlinePass::with(['user', 'transaction', 'onlineTicket'])->latest()->get();
        return view('admin.online_pass.user_online_pass', compact('passes'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $pass = UserOnlinePass::findOrFail($id);
        $pass->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'User Online Pass status updated successfully.');
    }
}
