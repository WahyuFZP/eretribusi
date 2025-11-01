<?php

namespace App\Http\Controllers;

use App\Models\InvoiceCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceCounterController extends Controller
{
    /**
     * Display a listing of the counters.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $counters = InvoiceCounter::orderBy('day', 'desc')->orderBy('key')->paginate($perPage);
        return response()->json($counters);
    }

    /**
     * Store a newly created counter.
     */
    public function store(Request $request)
    {
        $data = $request->only(['day', 'key', 'last_number']);
        $validator = Validator::make($data, [
            'day' => 'required|date',
            'key' => 'required|string|max:100',
            'last_number' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $counter = InvoiceCounter::create([
            'day' => $data['day'],
            'key' => strtoupper($data['key']),
            'last_number' => $data['last_number'] ?? 0,
        ]);

        return response()->json($counter, 201);
    }

    /**
     * Display the specified counter.
     */
    public function show(InvoiceCounter $invoiceCounter)
    {
        return response()->json($invoiceCounter);
    }

    /**
     * Update the specified counter.
     */
    public function update(Request $request, InvoiceCounter $invoiceCounter)
    {
        $data = $request->only(['last_number']);
        $validator = Validator::make($data, [
            'last_number' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $invoiceCounter->last_number = $data['last_number'];
        $invoiceCounter->save();

        return response()->json($invoiceCounter);
    }

    /**
     * Remove the specified counter.
     */
    public function destroy(InvoiceCounter $invoiceCounter)
    {
        $invoiceCounter->delete();
        return response()->json(['message' => 'deleted']);
    }

    /**
     * Atomically increment and return next counter for given day+key.
     * Expects 'day' (date) and 'key' (string) in the request.
     */
    public function increment(Request $request)
    {
        $data = $request->only(['day', 'key']);
        $validator = Validator::make($data, [
            'day' => 'required|date',
            'key' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $day = $data['day'];
        $key = strtoupper($data['key']);

        $next = null;

        DB::transaction(function () use ($day, $key, &$next) {
            $row = DB::table('invoice_counters')
                ->where('day', $day)
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if ($row) {
                $next = $row->last_number + 1;
                DB::table('invoice_counters')->where('id', $row->id)->update([
                    'last_number' => $next,
                    'updated_at' => now(),
                ]);
            } else {
                $next = 1;
                DB::table('invoice_counters')->insert([
                    'day' => $day,
                    'key' => $key,
                    'last_number' => $next,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json(['next' => $next]);
    }
}
