<?php
require('../vendor/autoload.php');
require('../Class/Config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP as SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Place to access mail service.
 * Class MailService
 */
class MailService
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mailSettings();
        $this->setSenderInformation();
    }

    /**
     * Place to configure mail service.
     * @return string
     */
    protected function mailSettings(): string
    {
        try {
            // Enable verbose debug output
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // Send using SMTP
            $this->mail->isSMTP();
            // Set the SMTP server to send through
            $this->mail->Host = gethostbyname(Config::SMTP_HOST);
            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $this->mail->Port = Config::SMTP_PORT;
            // Enable SMTP authentication
            $this->mail->SMTPAuth = true;
            // SMTP username
            $this->mail->Username = Config::SMTP_USER;
            // SMTP password
            $this->mail->Password = Config::SMTP_PASSWORD;
            // Mail Charset
            $this->mail->CharSet = 'UTF-8';
            // Mail Encoding
            $this->mail->Encoding = 'base64';
            // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $this->mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Add sender information.
     * @return string
     */
    protected function setSenderInformation(): string
    {
        try {
            $this->mail->setFrom('s.gopibabu@gmail.com', 'Gopibabu Srungavarapu');
            $this->mail->addReplyTo('s.gopibabu@gmail.com', 'Gopibabu Srungavarapu');
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Add Recipient Information.
     * @param array $recipients
     * @return string
     */
    public function setRecipientsInfo(array $recipients): string
    {
        try {
            foreach ($recipients as $key => $value) {
                $this->mail->addAddress($value, $key);
            }
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Add Attachments
     * @param array $attachments
     * @return string|
     */
    public function addAttachments(array $attachments): string
    {
        try {
            foreach ($attachments as $key => $value) {
                $this->mail->addAttachment($value, $key);
            }
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Add Subject and Body
     * @param string $subject
     * @param string $body
     * @param string $altBody
     * @return string
     */
    public function setEmailDetails(string $subject, string $body, string $altBody)
    {
        try {
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            if (isset($altBody)) {
                $this->mail->AltBody = strip_tags($altBody);
            }
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Send Email trigger
     * @return bool|string
     */
    public function sendEmail()
    {
        try {
            if ($this->mail->send()) {
                return true;
            }
        } catch (PHPMailerException $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}