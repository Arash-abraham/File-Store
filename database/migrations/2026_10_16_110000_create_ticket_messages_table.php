<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Support tickets become a conversation. Each message is either from the
 * customer (is_staff=false) or from support (is_staff=true).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_staff')->default(false);
            $table->text('body');
            $table->timestamps();

            $table->index(['ticket_id', 'id']);
        });

        // Every existing ticket becomes the first message (customer) and, when answered, the staff reply.
        DB::table('tickets')->orderBy('id')->chunkById(200, function ($tickets) {
            foreach ($tickets as $ticket) {
                $createdAt = $ticket->created_at ?? now();
                if (!empty($ticket->message)) {
                    DB::table('ticket_messages')->insert([
                        'ticket_id' => $ticket->id,
                        'user_id' => $ticket->user_id,
                        'is_staff' => false,
                        'body' => $ticket->message,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
                if (!empty($ticket->response)) {
                    $replyAt = $ticket->response_time ?? $createdAt;
                    DB::table('ticket_messages')->insert([
                        'ticket_id' => $ticket->id,
                        'user_id' => null,
                        'is_staff' => true,
                        'body' => $ticket->response,
                        'created_at' => $replyAt,
                        'updated_at' => $replyAt,
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_messages');
    }
};
