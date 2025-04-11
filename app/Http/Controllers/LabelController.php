<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabel;
use App\Models\Emotion;
use App\Models\Label;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
     // Show entries by labels
     public function show(Label $label)
     {
          $entries = $label->entries()
               ->where("delete", 0)
               ->orderByDesc('updated_at')
               ->get()->unique();

          $user = Auth::user();
          $useMenu = true;
          $currentLabel = $label;
          $title = $label->name . ' - Entry App';
          return view('entries.index', compact('user', 'entries', 'useMenu', 'currentLabel', 'title'));
     }

     public function addLabel(StoreLabel $request, Entry $entry)
     {
          if ($request->labels != null) {
               $this->authorize('author', [Label::class, $request->labels]);
               $entry->labels()->sync($request->labels);
          }

          if ($request['new_label'] !== null) {
               $label = Label::create([
                    'name' => $request['new_label'],
                    'user_id' => Auth::user()->id
               ]);
               $entry->labels()->attach($label);
          }
          return redirect()->route('entries.show', $entry)->with('info', 'Labels updated successfully');
     }

     public function update(Request $request)
     {
          $user = Auth::user();

          // Validate the request
          $request->validate([
               'labels' => 'array',
               'labels.*' => 'string|max:255',
               'id-labels' => 'array',
               'id-labels.*' => 'integer|exists:labels,id',
               'delete-labels' => 'array',
               'delete-labels.*' => 'integer|exists:labels,id',
               'new_label' => 'nullable|string|max:255|unique:labels,name',
          ]);

          // Update existing labels
          if ($request->has('labels') && $request->has('id-labels')) {
               $labels = array_combine($request->input('id-labels'), $request->input('labels'));

               foreach ($labels as $id => $name) {
                    $label = $user->labels()->find($id);
                    if ($label && $label->name !== $name) {
                         $label->update(['name' => $name]);
                    }
               }
          }

          // Delete selected labels
          if ($request->has('delete-labels')) {
               $user->labels()->whereIn('id', $request->input('delete-labels'))->delete();
          }

          // Create a new label if provided
          if ($request->filled('new_label')) {
               $user->labels()->create(['name' => $request->input('new_label')]);
          }

          return redirect()->route('entries.index')->with('info', 'Labels updated successfully.');
     }

     public function store(Request $request)
     {
          $request->validate([
               'name' => 'required|string|max:255|unique:labels,name'
          ]);

          $label = Label::create([
               'name' => $request->name,
               'user_id' => Auth::id()
          ]);

          return response()->json([
               'success' => true,
               'label' => $label
          ]);
     }
}