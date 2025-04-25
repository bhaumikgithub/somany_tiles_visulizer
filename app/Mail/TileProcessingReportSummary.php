<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TileProcessingReportSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $insertedCount;
    public $updatedCount;
    public $deletedCount;
    public $skippedCount;
    
    public $insertedSkus;
    public $updatedSkus;
    public $deletedSkus;
    public $skippedSkus;
    
    public $total;
    

    /**
     * Create a new message instance.
     */
    public function __construct($insertedCount, $updatedCount, $deletedCount, $skippedCount, $insertedSkus, $updatedSkus, $deletedSkus, $skippedSkus, $total)
    {
        $this->insertedCount = $insertedCount;
        $this->updatedCount = $updatedCount;
        $this->deletedCount = $deletedCount;
        $this->skippedCount = $skippedCount;

        $this->insertedSkus = $insertedSkus;
        $this->updatedSkus = $updatedSkus;
        $this->deletedSkus = $deletedSkus;
        $this->skippedSkus = $skippedSkus;

        $this->totalCount = $total;
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tile Processing Report Summary',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tile_processing_summary',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
