<?php
namespace App\Services;

use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->setup();
    }

    private function setup() {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['MAIL_USERNAME'];
            $this->mailer->Password = $_ENV['MAIL_PASSWORD'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $_ENV['MAIL_PORT'];
            $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $this->mailer->isHTML(true);
        } catch (Exception $e) {
            // Log error
            error_log("Mail setup error: {$e->getMessage()}");
        }
    }

    public function sendVerificationEmail(User $user, string $token) {
        try {
            $verifyUrl = $_ENV['APP_URL'] . "/verify-email/{$token}";
            
            $this->mailer->addAddress($user->email, $user->full_name);
            $this->mailer->Subject = 'Verify your email address';
            $this->mailer->Body = $this->getVerificationEmailTemplate($user->first_name, $verifyUrl);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Verification email error: {$e->getMessage()}");
            return false;
        }
    }

    public function sendPasswordResetEmail(User $user, string $token) {
        try {
            $resetUrl = $_ENV['APP_URL'] . "/reset-password/{$token}";
            
            $this->mailer->addAddress($user->email, $user->full_name);
            $this->mailer->Subject = 'Reset your password';
            $this->mailer->Body = $this->getPasswordResetEmailTemplate($user->first_name, $resetUrl);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Password reset email error: {$e->getMessage()}");
            return false;
        }
    }

    private function getVerificationEmailTemplate($name, $url) {
        return "
            <h2>Hello {$name}!</h2>
            <p>Thank you for registering with our Legal Document Management System.</p>
            <p>Please click the button below to verify your email address:</p>
            <p>
                <a href='{$url}' 
                   style='display: inline-block; background: #4F46E5; color: white; padding: 10px 20px; 
                          text-decoration: none; border-radius: 5px;'>
                    Verify Email Address
                </a>
            </p>
            <p>If you did not create an account, no further action is required.</p>
            <p>Regards,<br>Legal Document Management Team</p>
        ";
    }

    private function getPasswordResetEmailTemplate($name, $url) {
        return "
            <h2>Hello {$name}!</h2>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <p>Please click the button below to reset your password:</p>
            <p>
                <a href='{$url}' 
                   style='display: inline-block; background: #4F46E5; color: white; padding: 10px 20px; 
                          text-decoration: none; border-radius: 5px;'>
                    Reset Password
                </a>
            </p>
            <p>This password reset link will expire in 60 minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
            <p>Regards,<br>Legal Document Management Team</p>
        ";
    }
}