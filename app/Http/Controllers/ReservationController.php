<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Services\RabbitMQ\ReservationPublisher;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ReservationPublisher $publisher)
    {
        //
            $reservation = Reservation::create($request->all());

            $publisher->publishReservationCreated($reservation);

            return response()->json($reservation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        return response()->json($reservation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        $reservation->update($request->all());
        return response()->json($reservation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ReservationPublisher $publisher)
    {
        //
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        $reservation->delete();
        $publisher->publishReservationCanceled($reservation);

        return response()->json(['message' => 'Reservation deleted successfully']);
    }
}
