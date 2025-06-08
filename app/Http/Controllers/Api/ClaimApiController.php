<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Http\Resources\ClaimResource;
use App\Http\Resources\ClaimCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClaimApiController extends Controller
{
    /**
     * Display a listing of all claims.
     * (Admin only)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Claim::with('item');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type (claim/return)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $claims = $query->latest()->paginate(15);

        return response()->json([
            'data' => ClaimResource::collection($claims)->collection
        ]);
    }

    /**
     * Display claims of current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function myClaims(Request $request)
    {
        $query = Claim::with('item')
            ->where('claimer_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type (claim/return)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $claims = $query->latest()->paginate(15);

        return response()->json([
            'data' => ClaimResource::collection($claims)->collection
        ]);
    }

    /**
     * Display the specified claim.
     *
     * @param  int  $claim
     * @return \Illuminate\Http\Response
     */
    public function show($claim)
    {
        $claim = Claim::findOrFail($claim);

        return new ClaimResource($claim);
    }

    /**
     * Update the specified claim.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $claim
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $claim)
    {
        $claim = Claim::findOrFail($claim);

        // Regular user can only update pending claims
        if (Auth::id() === $claim->claimer_id && $claim->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot update claim. Only pending claims can be updated by the claimer.',
                'error' => 'INVALID_STATUS'
            ], 422);
        }

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,approved,rejected,Claimed',
            'ownership_proof' => 'sometimes|required|string',
            'claimer_phone' => 'sometimes|required|string|max:20',
            'notes' => 'nullable|string',
            'proof_document' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Upload proof document if provided
        if ($request->hasFile('proof_document')) {
            // Delete old document if exists
            if ($claim->proof_document) {
                Storage::disk('public')->delete($claim->proof_document);
            }

            $filePath = $request->file('proof_document')->store('claim_proofs', 'public');
            $validated['proof_document'] = $filePath;
        }

        $claim->update($validated);

        return new ClaimResource($claim);
    }

    /**
     * Remove the specified claim.
     *
     * @param  int  $claim
     * @return \Illuminate\Http\Response
     */
    public function destroy($claim)
    {
        $claim = Claim::findOrFail($claim);


        // Regular user can only delete pending claims
        if (Auth::id() === $claim->claimer_id && $claim->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot delete claim. Only pending claims can be deleted by the claimer.',
                'error' => 'INVALID_STATUS'
            ], 422);
        }

        // Delete proof document if exists
        if ($claim->proof_document) {
            Storage::disk('public')->delete($claim->proof_document);
        }

        $claim->delete();

        return response()->json(['message' => 'Claim deleted successfully']);
    }
}
