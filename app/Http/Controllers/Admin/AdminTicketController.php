<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $tickets = Ticket::all();
        $open_count = 0;
        $progress_count = 0;
        $close_count = 0;
    
        foreach($tickets as $ticket) {
            if($ticket->status == 'open') {
                $open_count += 1;
            }
            if($ticket->status == 'in_progress') {
                $progress_count += 1;
            }
            if($ticket->status == 'closed') {
                $close_count += 1;
            }
        }
        
        return view('admin.layouts.sections.tickets.tickets',compact('tickets','open_count','progress_count','close_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('admin.layouts.sections.tickets.show-ticket',compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // dd(now()['']);
        $ticket->update([
            'response' => $request->response,
            'response_time' => Carbon::now()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function process($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'in_progress';
        $ticket->save();
        return to_route('admin.ticket.index')->with('success','تغییرات شما با موفقیت ثبت شد');
    }
    public function closed($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();
        return to_route('admin.ticket.index')->with('success','تغییرات شما با موفقیت ثبت شد');
    }
}
