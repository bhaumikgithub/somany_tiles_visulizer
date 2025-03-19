<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TileProcessingReport extends Mailable
{
    use Queueable, SerializesModels;

    public $insertedRecords;
    public $updatedRecords;
    public $deletedRecords;


    /**
     * Create a new message instance.
     */
    public function __construct($insertedRecords, $updatedRecords, $deletedRecords)
    {
        $this->insertedRecords = is_array($insertedRecords) ? $insertedRecords : [];
        $this->updatedRecords = is_array($updatedRecords) ? $updatedRecords : [];
        $this->deletedRecords = is_array($deletedRecords) ? $deletedRecords : [];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        Log::info("Building email with from address: ", ['from' => 'no-reply@example.com']);

        return new Envelope(
            from: ['address' => 'no-reply@example.com', 'name' => 'Tile Processor'],
            subject: 'Tile Processing Report'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tile_processing_report',
            with: [
                'insertedRecords' => $this->insertedRecords,
                'updatedRecords' => $this->updatedRecords,
                'deletedRecords' => $this->deletedRecords,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
