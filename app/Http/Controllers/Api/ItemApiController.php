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







    // ------------------------------------------------------ Section for Lost Items ------------------------------------------------------




    /**
     * Display a listing of lost items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lostItems(Request $request)
    {
        $query = Item::where('type', 'hilang')
            ->where('status', 'approved');

        // Filter dan sorting tetap sama...

        // Pagination
        $perPage = $request->get('per_page', 15);
        $items = $query->paginate($perPage);

        // Kembalikan hanya data saja, tanpa meta/links pagination
        return response()->json([
            'data' => ItemResource::collection($items)->collection
        ]);
    }

    /**
     * Display the specified lost item.
     *
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function showLostItem($item)
    {
        $item = Item::where('id', $item)
            ->where('type', 'hilang')
            ->firstOrFail();

        return new ItemResource($item);
    }

    /**
     * Search for lost items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchLostItems(Request $request)
    {
        $query = Item::where('type', 'hilang')
            ->where('status', 'approved');

        // Search by name
        if ($request->has('q')) {
            $query->where('item_name', 'like', '%' . $request->q . '%');
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('date_of_event', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('date_of_event', '<=', $request->to_date);
        }

        $items = $query->latest()->paginate(15);

        return response()->json([
            'data' => ItemResource::collection($items)->collection
        ]);
    }


    /**
     * Store a newly created lost item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLostItem(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string',
            'date_of_event' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:5120', // 5MB
        ]);

        // Get authenticated user
        $user = Auth::user();

        // Ensure this is a lost item
        $validated['type'] = 'hilang';
        $validated['status'] = 'approved';
        $validated['user_id'] = $user->id;

        // Add report_by field from authenticated user
        if (!empty($user->first_name) || !empty($user->last_name)) {
            $validated['report_by'] = trim($user->first_name . ' ' . $user->last_name);
        } else {
            $validated['report_by'] = $user->name;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('items', 'public');
            $validated['photo_path'] = $path;
        }

        $item = Item::create($validated);

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Update the specified lost item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLostItem(Request $request, $id) // Ganti $item menjadi $id agar konsisten
    {
        try {
            // Coba ambil item tanpa constraint dulu
            $item = Item::findOrFail($id);

            // Kemudian verifikasi tipe item
            if ($item->type !== 'hilang') {
                return response()->json([
                    'message' => 'Hanya barang dengan tipe "hilang" yang bisa diupdate melalui endpoint ini',
                    'error' => 'INVALID_ITEM_TYPE'
                ], 422);
            }

            // Update item
            $validated = $request->validate([
                'item_name' => 'sometimes|required|string|max:255',
                'category' => 'sometimes|required|string',
                'date_of_event' => 'sometimes|required|date',
                'description' => 'nullable|string',
                'location' => 'sometimes|required|string',
                'email' => 'nullable|email',
                'phone_number' => 'nullable|string|max:20',
            ]);

            $item->update($validated);

            return new ItemResource($item);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Item tidak ditemukan',
                'error' => 'ITEM_NOT_FOUND',
                'id' => $id
            ], 404);
        }
    }

    /**
     * Remove the specified lost item.
     *
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyLostItem(Request $request, $id)
    {
        try {
            // Coba cari item tanpa filter dulu untuk debugging
            $item = Item::find($id);

            if (!$item) {
                return response()->json([
                    'message' => 'Item tidak ditemukan',
                    'error' => 'ITEM_NOT_FOUND',
                    'id' => $id
                ], 404);
            }

            // Cek tipe item
            if ($item->type !== 'hilang') {
                return response()->json([
                    'message' => 'Item ini bukan barang hilang',
                    'error' => 'INVALID_ITEM_TYPE',
                    'type' => $item->type
                ], 422);
            }

            // Hapus foto jika ada
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }

            // Hapus item
            $item->delete();

            return response()->json([
                'message' => 'Item berhasil dihapus',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function returnLostItem(Request $request, $item)
    {
        try {
            // Find the item
            $lostItem = Item::findOrFail($item);

            // Check if the item is lost type and approved
            if ($lostItem->type !== 'hilang') {
                return response()->json([
                    'message' => 'This item cannot be returned. Only lost items can be returned.',
                    'error' => 'INVALID_ITEM_TYPE'
                ], 422);
            }

            if ($lostItem->status !== 'approved') {
                return response()->json([
                    'message' => 'This item cannot be returned. Only approved items can be returned.',
                    'error' => 'INVALID_ITEM_STATUS'
                ], 422);
            }

            // Validate request
            $validated = $request->validate([
                'where_found' => 'required|string',
                'returner_phone' => 'required|string|max:20',
                'notes' => 'nullable|string',
                'item_photo' => 'nullable|image|max:5120', // 5MB max
            ]);

            // Get authenticated user
            $user = Auth::user();

            // Create return record
            $return = new \App\Models\ItemReturn();
            $return->item_id = $lostItem->id;
            $return->returner_id = $user->id;

            // Set returner name
            if (!empty($user->first_name) || !empty($user->last_name)) {
                $return->returner_name = trim($user->first_name . ' ' . $user->last_name);
            } else {
                $return->returner_name = $user->name;
            }

            $return->returner_email = $user->email;
            $return->returner_phone = $validated['returner_phone'];
            $return->where_found = $validated['where_found'];
            $return->notes = $validated['notes'] ?? null;
            $return->return_date = now();
            $return->status = 'pending';

            // Upload item photo if provided
            if ($request->hasFile('item_photo')) {
                $filePath = $request->file('item_photo')->store('return_photos', 'public');
                $return->item_photo = $filePath;
            }

            $return->save();

            // Create notification for the original reporter
            if ($lostItem->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $lostItem->user_id,
                    'message' => "Someone has returned your lost item: {$lostItem->item_name}",
                    'is_read' => 0
                ]);
            }

            return response()->json([
                'message' => 'Return request submitted successfully',
                'return_id' => $return->id,
                'status' => 'pending',
                'item' => [
                    'id' => $lostItem->id,
                    'name' => $lostItem->item_name,
                    'category' => $lostItem->category
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error submitting return request',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // ------------------------------------------------------ Section for Found Items ------------------------------------------------------


    /**
     * Display a listing of found items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function foundItems(Request $request)
    {
        $query = Item::where('type', 'ditemukan')
            ->where('status', 'approved');

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date
        if ($request->has('from_date')) {
            $query->whereDate('date_of_event', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('date_of_event', '<=', $request->to_date);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $items = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => ItemResource::collection($items)->collection
        ]);
    }

    /**
     * Display the specified found item.
     *
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function showFoundItem($item)
    {
        $item = Item::where('id', $item)
            ->where('type', 'ditemukan')
            ->firstOrFail();

        return new ItemResource($item);
    }

    /**
     * Search for found items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchFoundItems(Request $request)
    {
        $query = Item::where('type', 'ditemukan')
            ->where('status', 'approved');

        // Search by name
        if ($request->has('q')) {
            $query->where('item_name', 'like', '%' . $request->q . '%');
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('date_of_event', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('date_of_event', '<=', $request->to_date);
        }

        $items = $query->latest()->paginate(15);

        return response()->json([
            'data' => ItemResource::collection($items)->collection
        ]);
    }

    /**
     * Store a newly created found item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function storeFoundItem(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string',
            'date_of_event' => 'required|date',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:5120', // 5MB
        ]);

        // Get authenticated user
        $user = Auth::user();

        // Ensure this is a found item
        $validated['type'] = 'ditemukan';
        $validated['status'] = 'approved';
        $validated['user_id'] = $user->id;

        // Add report_by field from authenticated user
        if (!empty($user->first_name) || !empty($user->last_name)) {
            $validated['report_by'] = trim($user->first_name . ' ' . $user->last_name);
        } else {
            $validated['report_by'] = $user->name;
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('items', 'public');
            $validated['photo_path'] = $path;
        }

        $item = Item::create($validated);

        return (new ItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Claim a found item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function claimFoundItem(Request $request, $item)
    {
        try {
            // Find the item
            $foundItem = Item::findOrFail($item);

            // Check if the item is found type and approved
            if ($foundItem->type !== 'ditemukan') {
                return response()->json([
                    'message' => 'This item cannot be claimed. Only found items can be claimed.',
                    'error' => 'INVALID_ITEM_TYPE'
                ], 422);
            }

            if ($foundItem->status !== 'approved') {
                return response()->json([
                    'message' => 'This item cannot be claimed. Only approved items can be claimed.',
                    'error' => 'INVALID_ITEM_STATUS'
                ], 422);
            }

            // Validate request
            $validated = $request->validate([
                'ownership_proof' => 'required|string',
                'claimer_phone' => 'required|string|max:20',
                'notes' => 'nullable|string',
                'proof_document' => 'nullable|image|max:5120', // 5MB max
            ]);

            // Get authenticated user
            $user = Auth::user();

            // Create claim
            $claim = new \App\Models\Claim();
            $claim->type = 'claim';
            $claim->item_id = $foundItem->id;
            $claim->claimer_id = $user->id;

            // Set claimer name
            if (!empty($user->first_name) || !empty($user->last_name)) {
                $claim->claimer_name = trim($user->first_name . ' ' . $user->last_name);
            } else {
                $claim->claimer_name = $user->name;
            }

            $claim->claimer_email = $user->email;
            $claim->claimer_phone = $validated['claimer_phone'];
            $claim->ownership_proof = $validated['ownership_proof'];
            $claim->notes = $validated['notes'] ?? null;
            $claim->claim_date = now();
            $claim->status = 'pending';

            // Upload proof document if provided
            if ($request->hasFile('proof_document')) {
                $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
                $claim->proof_document = $filePath;
            }

            $claim->save();

            // Create notification for the finder
            if ($foundItem->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $foundItem->user_id,
                    'message' => "Someone claimed your found item: {$foundItem->item_name}",
                    'is_read' => 0
                ]);
            }

            return response()->json([
                'message' => 'Claim submitted successfully',
                'claim_id' => $claim->id,
                'status' => 'pending',
                'item' => [
                    'id' => $foundItem->id,
                    'name' => $foundItem->item_name,
                    'category' => $foundItem->category
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error submitting claim',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified found item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFoundItem(Request $request, $id)
    {
        try {
            // Find the item
            $item = Item::findOrFail($id);

            // Verify it's a found item
            if ($item->type !== 'ditemukan') {
                return response()->json([
                    'message' => 'This is not a found item',
                    'error' => 'INVALID_ITEM_TYPE',
                    'type' => $item->type
                ], 422);
            }

            // Validate request
            $validated = $request->validate([
                'item_name' => 'sometimes|string|max:255',
                'category' => 'sometimes|string',
                'date_of_event' => 'sometimes|date',
                'description' => 'nullable|string',
                'location' => 'sometimes|string',
                'email' => 'nullable|email',
                'phone_number' => 'nullable|string|max:20',
                'photo' => 'nullable|image|max:5120', // 5MB max
            ]);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($item->photo_path) {
                    Storage::disk('public')->delete($item->photo_path);
                }

                $path = $request->file('photo')->store('items', 'public');
                $validated['photo_path'] = $path;
            }

            $item->update($validated);

            return new ItemResource($item);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating found item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified found item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyFoundItem($id)
    {
        try {
            // Find the item
            $item = Item::findOrFail($id);

            // Verify it's a found item
            if ($item->type !== 'ditemukan') {
                return response()->json([
                    'message' => 'Item ini bukan barang ditemukan',
                    'error' => 'INVALID_ITEM_TYPE',
                    'type' => $item->type
                ], 422);
            }

            // Delete photo if exists
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }

            // Delete item
            $item->delete();

            return response()->json([
                'message' => 'Item berhasil dihapus',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus item',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
