<?php

namespace App\Helpers;

/**
 * SECURITY: Custom Security Helper
 *
 * Provides security utility functions used across the application:
 * - Log injection prevention (Module 4 - OWASP A9)
 * - Input sanitization helpers
 */
class SecurityHelper
{
    /**
     * SECURITY (Module 4): Sanitize input to prevent Log Injection.
     *
     * Strips CRLF characters (\r, \n) and other control characters
     * that could be used to forge log entries or inject malicious
     * data into system logs.
     *
     * OWASP Reference: Log Injection / A9 Security Logging Failures
     *
     * @param string $input The raw input string
     * @return string Sanitized string safe for log storage
     */
    public static function sanitizeLogInput(string $input): string
    {
        // Remove carriage return (\r) and newline (\n) characters
        $sanitized = str_replace(["\r\n", "\r", "\n"], ' ', $input);

        // Remove other potentially dangerous control characters (ASCII 0-31 except space)
        $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $sanitized);

        // Trim excessive whitespace
        $sanitized = trim(preg_replace('/\s+/', ' ', $sanitized));

        return $sanitized;
    }

    /**
     * Validate an IFA (Interface Agreement) request payload.
     * Ensures requestID and timestamp are present.
     *
     * @param array $data Request data
     * @return array Validated data with defaults
     */
    public static function validateIfaRequest(array $data): array
    {
        return [
            'requestID' => $data['requestID'] ?? uniqid('REQ-'),
            'timestamp' => $data['timestamp'] ?? now()->toIso8601String(),
        ];
    }

    /**
     * Generate a standardized IFA response envelope.
     *
     * @param string $status 'S' (Success), 'F' (Failure), 'E' (Error)
     * @param mixed $data Response data
     * @param string|null $message Optional message
     * @return array IFA-compliant response array
     */
    public static function ifaResponse(string $status, mixed $data = null, ?string $message = null): array
    {
        $response = [
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($message !== null) {
            $response['message'] = $message;
        }

        return $response;
    }
}
