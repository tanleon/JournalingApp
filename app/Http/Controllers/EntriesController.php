<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;

class EntriesController extends Controller
{
    // ...existing code...

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'emotion_id' => 'required|exists:emotions,id',
            'labels' => 'array',
            'labels.*' => 'exists:labels,id',
        ]);

        $entry = Entry::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'emotion_id' => $validated['emotion_id'],
            'created_at' => now(),
        ]);

        if (isset($validated['labels'])) {
            $entry->labels()->attach($validated['labels']);
        }

        return redirect()->route('entries.index')->with('success', 'Entry created successfully!');
    }

    // ...existing code...
}