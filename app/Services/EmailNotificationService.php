<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailNotificationService
{
    public function send(string $to, string $subject, string $htmlMessage, ?string $textMessage = null): bool
    {
        $to = trim($to);
        if ($to === '') {
            return false;
        }

        $config = config('Email');

        // Se não houver config mínima, tenta o serviço padrão do CI
        if (empty($config->fromEmail)) {
            return false;
        }

        // Preferência: SMTP configurado
        if (($config->protocol ?? '') === 'smtp' && !empty($config->SMTPHost)) {
            return $this->sendWithPhpMailer($to, $subject, $htmlMessage, $textMessage);
        }

        // Fallback: Email service do CodeIgniter
        $email = service('email');
        $email->setMailType('html');
        $email->setFrom($config->fromEmail, $config->fromName ?: 'Rifas');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($htmlMessage);

        return (bool) $email->send(false);
    }

    private function sendWithPhpMailer(string $to, string $subject, string $htmlMessage, ?string $textMessage = null): bool
    {
        $config = config('Email');

        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = $config->charset ?? 'UTF-8';
            $mail->isSMTP();
            $mail->Host = $config->SMTPHost;
            $mail->Port = (int) ($config->SMTPPort ?? 587);
            $mail->SMTPAuth = true;
            $mail->Username = (string) ($config->SMTPUser ?? '');
            $mail->Password = (string) ($config->SMTPPass ?? '');

            $crypto = (string) ($config->SMTPCrypto ?? 'tls');
            if ($crypto === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($crypto === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = false;
            }

            $mail->setFrom($config->fromEmail, $config->fromName ?: 'Rifas');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;
            $mail->AltBody = $textMessage ?: strip_tags($htmlMessage);

            return $mail->send();
        } catch (\Throwable $e) {
            log_message('warning', 'Falha ao enviar email via PHPMailer: {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
