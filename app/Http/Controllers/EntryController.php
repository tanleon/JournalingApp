<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntry; // Fixed: Changed from StoreNote to StoreEntry
use App\Models\Emotion;
use App\Models\Entry;
use App\Models\Label; // Added Label model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EntryController extends Controller
{
     // Display entries
     public function index()
     {
          $user = Auth::user();
          $entries = $user->entries()->where("delete", 0)->orderByDesc('updated_at')->get();
          $useMenu = true;
          return view('entries.index', compact('user', 'entries', 'useMenu'));
     }

     // Create entry view
     public function create()
     {
          $emotions = Emotion::all();

          // Pass emotions to the view
          return view('entries.create', [
               'emotions' => $emotions,
          ]);
     }

     // Store a new entry
     public function store(Request $request) 
     {
          $request->validate([
               'title' => 'required|string|max:255',
               'body' => 'required|string',
               'created_at' => 'nullable|date_format:Y-m-d\TH:i', // Allow nullable created_at
               'emotion_id' => 'required|exists:emotions,id',
          ]);

          $entry = Entry::create([
               'title' => $request->title, 
               'body' => $request->body,
               'created_at' => $request->created_at ?? now(), // Default to current time if not provided
               'emotion_id' => $request->emotion_id,
               'user_id' => Auth::id(),
          ]);
          // Attach selected labels to the entry
          if ($request->has('labels')) {
               $entry->labels()->sync($request->input('labels'));
          }

          return redirect()->route('entries.show', $entry)->with('success', 'Entry created successfully.');
     }

     // Show an entry
     public function show(Entry $entry)
     {
          $this->authorize('author', $entry);
          $user = Auth::user();
          $emotions = Emotion::all();
          $edit = true;
          $title = $entry->title . ' - Entry App';
          return view('entries.show', compact('user', 'entry', 'emotions', 'edit', 'title'));
     }

     // Show entry with labels edit alert
     public function showLabelsEdit(Entry $entry)
     {
          $this->authorize('author', $entry);
          return redirect()->route('entries.show', $entry)->with('labels_active_event', true);
     }

     // Make a copy of an entry
     public function makeCopy(Entry $entry)
     {
          $this->authorize('author', $entry);

          $clone = $entry->replicate();
          $clone->push();
          $clone->title .= " - Copy";
          $clone->labels()->sync($entry->labels);
          $clone->save();
          return redirect()->route('entries.index')->with('info', 'Entry created successfully');
     }

     // Show entry in read-only mode
     public function showReadOnly(Entry $entry)
     {
          $this->authorize('author', $entry);

          $user = Auth::user();
          $emotions = Emotion::all();
          $readOnly = true;
          $title = $entry->title . ' - Entry App';
          $labels = $entry->labels; // Include labels associated with the entry
          return view('entries.show', compact('user', 'entry', 'emotions', 'readOnly', 'title', 'labels'));
     }

     // Update an entry
     public function update(Request $request, Entry $entry)
     {
          $request->validate([
               'title' => 'required|string|max:255',
               'body' => 'required|string',
               'created_at' => 'nullable|date_format:Y-m-d\TH:i', // Allow nullable created_at
               'emotion_id' => 'required|exists:emotions,id',
               'labels' => 'array',
               'labels.*' => 'integer|exists:labels,id',
               'new_label' => 'nullable|string|max:255|unique:labels,name',
          ]);

          // Update entry details
          $entry->update([
               'title' => $request->title,
               'body' => $request->body,
               'created_at' => $request->created_at ?? $entry->created_at, // Keep existing created_at if not provided
               'emotion_id' => $request->emotion_id,
          ]);

          // Sync existing labels
          if ($request->has('labels')) {
               $entry->labels()->sync($request->labels);
          } else {
               $entry->labels()->detach(); // Detach all labels if none are selected
          }

          // Add new label if provided
          if ($request->filled('new_label')) {
               $newLabel = Label::create([
                    'name' => $request->new_label,
                    'user_id' => Auth::id(),
               ]);
               $entry->labels()->attach($newLabel->id);
          }

          return redirect()->route('entries.show', $entry)->with('success', 'Entry updated successfully.');
     }

     // Delete an entry
     public function destroy(Entry $entry)
     {
          $this->authorize('author', $entry);
          $entry->delete();
          return redirect()->route('entries.trash')->with('info', 'Entry deleted successfully');
     }

     // Move an entry to trash
     public function sendTrash(Entry $entry)
     {
          // Ensure the user is authorized to perform this action
          $this->authorize('author', $entry);

          $entry->delete = 1;
          $entry->save();

          return redirect()->route('entries.index')->with('info', 'Entry moved to trash');
     }

     // Display trash view
     public function trash()
     {
          $user = Auth::user();
          $entries = $user->entries()->where("delete", 1)->orderByDesc('updated_at')->get();
          $useMenu = true;
          $trash = true;
          $title = 'Trash - Entry App';

          return view('entries.index', compact('user', 'entries', 'useMenu', 'trash', 'title'));
     }

     // Empty trash
     public function emptyTrash(Request $request)
     {
          $entries = Auth::user()->entries->where("delete", 1);
          $nEntries = $entries->count();

          if ($nEntries > 0) {
               foreach ($entries as $entry) {
                    $entry->delete();
               }
          }

          if ($nEntries == 0) {
               $message = "There are no entries";
               return redirect()->route('entries.trash')->withErrors($message);
          }

          $message = $nEntries == 1 ? "Entry deleted forever" : "Entries deleted forever";
          return redirect()->route('entries.trash')->with('info', $message);
     }

     // Restore an entry from trash
     public function restore(Entry $entry)
     {
          $this->authorize('author', $entry);
          $entry->delete = 0;
          $entry->save();
          return redirect()->route('entries.trash')->with('info', 'Entry restored successfully');
     }

     // Search for entries
     public function search(Request $request)
     {
          $search = $request->search;
          if (empty($search)) {
               return redirect()->route("entries.index");
          }
          return redirect()->route("entries.searchView", $search);
     }

     // Display search results
     public function searchView($search)
     {
          $user = Auth::user();
          $entries = Entry::where([
               ["user_id", $user->id],
               ["delete", 0]
          ])->where(function ($query) use ($search) {
               $query
                    ->where("title", 'LIKE', "%{$search}%")
                    ->orWhere('body', 'LIKE', "%{$search}%");
          })->get();

          $useMenu = true;
          return view('entries.index', compact('user', 'entries', 'useMenu', 'search'));
     }

     // Close tags - function to build abstract entries
     public function closetags($html)
     {
          preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
          $openedtags = $result[1];
          preg_match_all('#</([a-z]+)>#iU', $html, $result);
          $closedtags = $result[1];
          $len_opened = count($openedtags);
          if (count($closedtags) == $len_opened) {
               return $html;
          }
          $openedtags = array_reverse($openedtags);
          for ($i = 0; $i < $len_opened; $i++) {
               if (!in_array($openedtags[$i], $closedtags)) {
                    $html .= '</' . $openedtags[$i] . '>';
               } else {
                    unset($closedtags[array_search($openedtags[$i], $closedtags)]);
               }
          }
          return $html;
     }
}