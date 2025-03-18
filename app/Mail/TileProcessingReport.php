<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
        $this->insertedRecords = $insertedRecords;
        $this->updatedRecords = $updatedRecords;
        $this->deletedRecords = $deletedRecords;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): TileProcessingReport
    {
        return $this->from('no-reply@example.com', 'Tile Processor')
            ->subject('Tile Processing Report')
            ->view('emails.tile_report')
            ->with([
                'insertedRecords' => $this->insertedRecords,
                'updatedRecords' => $this->updatedRecords,
                'deletedRecords' => $this->deletedRecords,
            ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
