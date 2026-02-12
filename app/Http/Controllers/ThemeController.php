<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Update the user's theme preference.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $user = auth()->user();
        if ($user) {
            $user->update(['theme' => $validated['theme']]);
            return response()->json(['status' => 'success', 'theme' => $user->theme]);
        }

        return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }
}
