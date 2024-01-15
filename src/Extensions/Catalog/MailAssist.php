<?php
namespace App\Extensions\Catalog;

use PHPMailer\PHPMailer\PHPMailer;
use Simflex\Core\Core;

class MailAssist
{
    protected $address;
    protected $additionalAddress;
    protected $subject;
    protected $template;
    protected $data;
    protected $body;
    protected $attachment;
    protected $attachmentName;

    public function __construct(string $to, string $subject)
    {
        $this->address = $to;
        $this->subject = $subject;
    }

    public function tpl(string $template, array $data = []): self
    {
        $this->template = $template;
        $this->data = $data;
        return $this;
    }

    public function content(string $data): self
    {
        $this->body = $data;
        return $this;
    }

    public function alsoTo(string $param): self
    {
        $this->additionalAddress = Core::siteParam($param);
        return $this;
    }

    public function file(string $path, string $name): self
    {
        $this->attachment = $path;
        $this->attachmentName = $name;
        return $this;
    }

    public function send()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = env('MAIL_SECURITY');
        $mail->Host = env('MAIL_HOST');
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->FromName = Core::siteParam('site_name');
        $mail->From = env('MAIL_USERNAME');
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];

        $mail->addReplyTo(Core::siteParam('form_email'));

        $mail->addAddress($this->address);
        if ($this->additionalAddress) {
            $mail->addAddress($this->additionalAddress);
        }

        if ($this->attachment) {
            $mail->addAttachment($this->attachment, $this->attachmentName);
        }

        $mail->isHTML();
        if (!$this->body) {
            $this->body = $this->runTemplate();
        }

        $mail->Subject = $this->subject;
        $mail->Body = $this->body;
        return $mail->send();
    }

    protected function runTemplate()
    {
        ob_start();
        extract($this->data ?? []);
        include __DIR__ . '/tpl/mail/' . $this->template;
        return ob_get_clean();
    }
}