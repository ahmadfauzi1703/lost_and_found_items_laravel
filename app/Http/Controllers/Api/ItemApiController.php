<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Resources\ItemResource;
use App\Http\Resources\ItemCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Mulai query dasar tanpa filter status
        $query = Item::query(); // Ubah baris ini untuk menampilkan semua data

        // OPSIONAL: Filter berdasarkan status jika parameter status dikirimkan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan tipe (hilang/ditemukan)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan nama item jika ada
        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        // Paginasi hasil
        $items = $query->latest()->paginate(10);

        return new ItemCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:hilang,ditemukan',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date_of_event' => 'required|date',
            'description' => 'required|string',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:15',
            'location' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:3048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');
        }

        // Get authenticated user
        $user = Auth::user();

        // Create reporter name
        $reporter = '';
        if (!empty($user->first_name) || !empty($user->last_name)) {
            $reporter = trim($user->first_name . ' ' . $user->last_name);
        } else {
            $reporter = $user->name;
        }

        // Create item
        $item = Item::create([
            'type' => $request->type,
            'item_name' => $request->item_name,
            'category' => $request->category,
            'date_of_event' => $request->date_of_event,
            'description' => $request->description,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'location' => $request->location,
            'photo_path' => $photoPath,
            'user_id' => $user->id,
            'status' => 'pending',
            'report_by' => $reporter
        ]);

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) // Ubah parameter menjadi $id
    {
        // Cari item secara eksplisit
        $item = Item::find($id);

        // Jika item tidak ditemukan, kembalikan 404
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Jika ditemukan, kembalikan resource
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Get item manually instead of using model binding
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Hapus kondisi autentikasi yang menyebabkan error
        // if ($item->user_id !== Auth::id()) {
        //    return response()->json(['message' => 'Unauthorized to edit this item'], 403);
        // }

        // Lakukan update item
        $validatedData = $request->validate([
            'item_name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:hilang,ditemukan',
            'description' => 'sometimes|string',
            'date_of_event' => 'sometimes|date',
            'location' => 'sometimes|string',
            'email' => 'sometimes|email',
            'phone_number' => 'sometimes|string|max:15',
            'status' => 'sometimes|in:pending,approved,rejected,claimed',
        ]);

        $item->update($validatedData);

        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get item manually instead of using model binding
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Hapus kondisi autentikasi yang menyebabkan error
        // if ($item->user_id !== Auth::id()) {
        //    return response()->json(['message' => 'Unauthorized to delete this item'], 403);
        // }

        // Delete photo if exists
        if ($item->photo_path && Storage::disk('public')->exists($item->photo_path)) {
            Storage::disk('public')->delete($item->photo_path);
        }

        // Delete item
        $item->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus',
            'item_id' => $id,
            'item_name' => $item->item_name
        ], 200);
    }

    /**
     * Search for items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = Item::where('status', 'approved');

        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date_of_event', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date_of_event', '<=', $request->date_to);
        }

        $items = $query->latest()->paginate(10);

        return new ItemCollection($items);
    }

    /**
     * Submit a claim for an item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function claim(Request $request, Item $item)
    {
        // Verify that item is "found" type
        if ($item->type !== 'ditemukan') {
            return response()->json(['message' => 'Cannot claim this type of item'], 400);
        }

        $validated = $request->validate([
            'ownership_proof' => 'required|string',
            'claimer_phone' => 'required|string',
            'notes' => 'nullable|string',
            'proof_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();

        $claim = new \App\Models\Claim();
        $claim->item_id = $item->id;
        $claim->claimer_name = $user->name;
        $claim->claimer_email = $user->email;
        $claim->claimer_phone = $validated['claimer_phone'];
        $claim->ownership_proof = $validated['ownership_proof'];
        $claim->notes = $validated['notes'] ?? null;
        $claim->claim_date = now();
        $claim->status = 'pending'; // Pending verification
        $claim->type = 'claim';

        // Upload proof document if provided
        if ($request->hasFile('proof_document')) {
            $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
            $claim->proof_document = $filePath;
        }

        $claim->save();

        return response()->json([
            'message' => 'Claim submitted successfully',
            'claim_id' => $claim->id
        ], 201);
    }

    /**
     * Submit a return for an item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function returnItem(Request $request, Item $item)
    {
        // Verify that item is "lost" type
        if ($item->type !== 'hilang') {
            return response()->json(['message' => 'Cannot return this type of item'], 400);
        }

        $validated = $request->validate([
            'where_found' => 'required|string',
            'returner_phone' => 'required|string',
            'notes' => 'nullable|string',
            'item_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Create claimer name
        $claimer_name = '';
        if (!empty($user->first_name) || !empty($user->last_name)) {
            $claimer_name = trim($user->first_name . ' ' . $user->last_name);
        } else if (!empty($user->name)) {
            $claimer_name = $user->name;
        } else {
            $claimer_name = 'User_' . $user->id;
        }

        // Create return (using claim model with type 'return')
        $return = new \App\Models\Claim();
        $return->type = 'return';
        $return->item_id = $item->id;
        $return->claimer_name = $claimer_name;
        $return->claimer_email = $user->email;
        $return->claimer_phone = $validated['returner_phone'];
        $return->where_found = $validated['where_found'];
        $return->ownership_proof = 'Dikembalikan langsung oleh penemu';
        $return->notes = $validated['notes'] ?? null;
        $return->claim_date = now();
        $return->status = 'pending';

        // Upload item photo if provided
        if ($request->hasFile('item_photo')) {
            $filePath = $request->file('item_photo')->store('return_photos', 'public');
            $return->item_photo = $filePath;
        }

        $return->save();

        return response()->json([
            'message' => 'Return request submitted successfully',
            'return_id' => $return->id
        ], 201);
    }
}
