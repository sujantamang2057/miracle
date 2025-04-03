<?php

namespace Modules\Common\app\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Modules\Sys\app\Models\EmailTemplate;

class CommonMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $mailCode;

    protected string $mailReplyTo;

    protected string $mailSubject;

    protected string $content;

    protected string $componentName;

    protected array $data = [];

    protected array $files;

    /**
     * Create a new message instance.
     */
    //
    public function __construct(string $mailCode = '', string $replyTo = '', array $data = [], string $subject = '', string $content = '', $attachments = [])
    {
        $this->mailCode = $mailCode;
        $this->mailReplyTo = $replyTo;
        $this->componentName = 'emailTemplates.' . $mailCode;
        $this->mailSubject = $subject;
        $this->data = $data;
        $this->files = $attachments;
        $this->content = '';

        if (empty($mailCode)) {
            $this->content = $content;
            $this->componentName = '';
        } else {
            $emailTemplate = $this->getMailTemplate();
            if (!empty($emailTemplate)) {
                if (!view()->exists('components.emailTemplates.' . $emailTemplate->mail_code)) {
                    $emailTemplate::regenerateBlock($emailTemplate->publish, $emailTemplate->mail_code, $emailTemplate->contents);
                }
                $this->setDataVariables($emailTemplate->variables);
                $this->mailSubject = empty($this->mailSubject) ? $emailTemplate->subject : $this->mailSubject;
            }
        }
    }

    /**
     * Build the message.
     */
    public function build(): self
    {

        return $this->subject($this->mailSubject)
            ->replyTo($this->mailReplyTo)
            ->view('common::mails.common')
            ->with('componentName', $this->componentName)
            ->with('content', $this->content)
            ->with('data', $this->data);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if (!empty($this->files) && is_array($this->files)) {
            foreach ($this->files as $key => $file) {
                $attachments[] = Attachment::fromPath($file['path'])
                    ->as($file['fileName']);
            }
        }

        return $attachments;
    }

    protected function getMailTemplate()
    {
        $mailCode = $this->mailCode;
        $emailTemplate = EmailTemplate::published()->where('mail_code', $mailCode)->first();

        return $emailTemplate;
    }

    protected function setDataVariables($variables)
    {
        $varArr = !empty($variables) ? explode(',', $variables) : [];
        if (!empty($varArr)) {
            foreach ($varArr as $key => $variable) {
                if (!isset($this->data[$variable])) {
                    $this->data[$variable] = '';
                }
            }
        }
    }
}
