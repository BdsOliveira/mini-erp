<?php

namespace Framework\Utils;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private static $config = null;
    private static $provider = null;

    public static function config(): void
    {
        if (self::$config === null) {
            self::$config = include __DIR__ . '/../../config/mail.php';
            self::$provider = $_ENV['MAIL_PROVIDER'] ?? self::$config['smtp'];
        }
    }

    private static function getMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = self::$config['providers'][self::$provider]['host'];
        $mailer->SMTPAuth = true;
        $mailer->Username = self::$config['providers'][self::$provider]['username'];
        $mailer->Password = self::$config['providers'][self::$provider]['password'];
        $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailer->Port = self::$config['providers'][self::$provider]['port'];

        $mailer->setFrom(self::$config['providers'][self::$provider]['from_address'], self::$config['providers'][self::$provider]['from_name']);

        return $mailer;
    }


    public static function send(string $to, string $subject, string $body, bool $isHtml = false): string
    {
        self::config();
        $mailer = self::getMailer();

        $mailer->addAddress($to);

        $mailer->isHTML($isHtml);

        $mailer->Subject = $subject;
        $mailer->Body = $body;

        if (!$mailer->send()) {
            return "Erro no envio do email: {$mailer->ErrorInfo}";
        } else {
            return 'Email enviado com sucesso!';
        }
    }
}