<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();
        return view('members.index', compact('members'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'telephone' => 'required|string|max:15|unique:members,telephone',
        ]);

        Member::create([
            'name' => $request->name,
            'telephone' => $request->telephone,
            'point' => 0,  // Default 0
            'status' => 'active'  // Default active
        ]);

        return redirect()->route('members.index')->with('success', 'Member added successfully!');
    }


    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    public function search(Request $request)
    {
        $telephone = $request->input('telephone');
        if (!$telephone) {
            return response()->json(['error' => 'Telephone is required'], 400);
        }

        $member = Member::where('telephone', $telephone)->first();

        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        return response()->json([
            'id' => $member->id_member,
            'name' => $member->name,
            'point' => $member->point,
            'status' => $member->status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'telephone' => 'required|string|max:15|unique:members,telephone,' . $id . ',id_member',
            'point' => 'required|integer',
            'status' => 'required|in:active,non-active',
        ]);

        $member = Member::findOrFail($id);
        $member->update([
            'name' => $request->name,
            'telephone' => $request->telephone,
            'point' => $request->point,
            'status' => $request->status,
        ]);

        return redirect()->route('members.index')->with('success', 'Member updated successfully!');
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        if($member->status == 'non-active'){
            $member->delete();
            return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Cannot delete member with status is active.');
        }
    }
}
