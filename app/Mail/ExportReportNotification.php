<?php

namespace Vanguard\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExportReportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reportUrl;

    /**
     * Create a new message instance.
     *
     * @param string $reportUrl
     */
    public function __construct($reportUrl)
    {
        $this->reportUrl = $reportUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Export Report Notification')
                    ->view('emails.export_report_notification');
    }
}
