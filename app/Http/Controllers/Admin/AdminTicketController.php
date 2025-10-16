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
        $userId = auth()->id();
        
        $validated = $request->validate([
            'subject' => [
                'required',
                'string',
                'max:200',
            ],
            'assigned_to' => 'required',
            'message' => 'required|string',
        ]);

        $validated['user_id'] = $userId;

        Ticket::create($validated);

        return redirect()->back()->with('success', 'تیکت با موفقیت ثبت شد ، ادمین ها با شما تماس خواهند گرفت');

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
        $validated = $request->validate([
            'response' => [
                'required',
                'string',
                'max:500',
                'regex:/^[\pL\pN\pM\s\-_.,!?؛،؟()\[\]{}:؛]+$/u'
            ]
        ], [
            'response.regex' => 'پاسخ به تیکت فقط می‌تواند شامل حروف، اعداد و کاراکترهای مجاز باشد.',
        ]);
        $ticket->update([
            'response' => $validated['response'],
            'response_time' => Carbon::now()
        ]);
        $ticket->status = 'closed';
        $ticket->save();
        return to_route('admin.ticket.index')->with('success','پاسخ شما به تیکت کاربر با موفقیت ثبت شد');
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
        return back()->with('success','وضعیت تیکت با موفقیت به درحال بررسی تغییر یافت');
    }
    public function closed($id) {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();
        return to_route('admin.ticket.index')->with('success','تیکت با موفقیت بسته شد');
    }
}
