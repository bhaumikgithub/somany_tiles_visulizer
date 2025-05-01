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
use Illuminate\Mail\Mailables\Address; //Import Address class

class TileProcessingReport extends Mailable
{
    use Queueable, SerializesModels;

    public $insertedRecords;
    public $updatedRecords;
    public $deletedRecords;
    public $skippedRecords;


    /**
     * Create a new message instance.
     */
    public function __construct($insertedRecords, $updatedRecords, $deletedRecords , $skippedRecords)
    {
        $this->insertedRecords = is_array($insertedRecords) ? $insertedRecords : [];
        $this->updatedRecords = is_array($updatedRecords) ? $updatedRecords : [];
        $this->deletedRecords = is_array($deletedRecords) ? $deletedRecords : [];
        $this->skippedRecords = is_array($skippedRecords) ? $skippedRecords : [];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('no-reply@tilesvisualizer.com', 'Tile Processor'), // Correct format
            subject: 'Tile Processing Report From Somany Server'
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
                'skippedRecords' => $this->skippedRecords,
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
