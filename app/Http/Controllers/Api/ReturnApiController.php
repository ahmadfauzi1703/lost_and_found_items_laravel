<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemReturn; 
use App\Http\Resources\ReturnResource; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReturnApiController extends Controller
{
    /**
     * Display a listing of all returns.
     * (Admin only)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = ItemReturn::with('item');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(15);

        return response()->json([
            'data' => ReturnResource::collection($returns)->collection
        ]);
    }

    /**
     * Display returns of current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function myReturns(Request $request)
    {
        $query = ItemReturn::with('item')
            ->where('returner_id', Auth::id()); 

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(15);

        return response()->json([
            'data' => ReturnResource::collection($returns)->collection
        ]);
    }

    /**
     * Display the specified return.
     *
     * @param  int  $return
     * @return \Illuminate\Http\Response
     */
    public function show($return)
    {
        $returnItem = ItemReturn::findOrFail($return);


        return new ReturnResource($returnItem);
    }

    /**
     * Update the specified return.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $return
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $return)
    {
        $returnItem = ItemReturn::findOrFail($return);

        // Regular user can only update pending returns
        if (Auth::id() === $returnItem->returner_id && $returnItem->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot update return. Only pending returns can be updated by the returner.',
                'error' => 'INVALID_STATUS'
            ], 422);
        }

        $validated = $request->validate([
            'where_found' => 'sometimes|required|string',
            'returner_phone' => 'sometimes|required|string|max:20', 
            'notes' => 'nullable|string',
            'item_photo' => 'nullable|image|max:5120', 
        ]);


        // Upload item photo if provided
        if ($request->hasFile('item_photo')) {
            // Delete old photo if exists
            if ($returnItem->item_photo) {
                Storage::disk('public')->delete($returnItem->item_photo);
            }

            $filePath = $request->file('item_photo')->store('return_photos', 'public');
            $validated['item_photo'] = $filePath;
        }

        $returnItem->update($validated);

        return new ReturnResource($returnItem);
    }

    /**
     * Remove the specified return.
     *
     * @param  int  $return
     * @return \Illuminate\Http\Response
     */
    public function destroy($return)
    {
        $returnItem = ItemReturn::findOrFail($return);

        // Regular user can only delete pending returns
        if (Auth::id() === $returnItem->returner_id && $returnItem->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot delete return. Only pending returns can be deleted by the returner.',
                'error' => 'INVALID_STATUS'
            ], 422);
        }

        // Delete item photo if exists
        if ($returnItem->item_photo) {
            Storage::disk('public')->delete($returnItem->item_photo);
        }

        $returnItem->delete();

        return response()->json(['message' => 'Return deleted successfully']);
    }
}
