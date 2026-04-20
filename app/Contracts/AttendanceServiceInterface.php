<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;
use App\Models\Attendance;

interface AttendanceServiceInterface
{
    /**
     * Store the attachment file.
     * 
     * @param UploadedFile $file
     * @return string The stored file path
     */
    public function storeAttachment(UploadedFile $file): string;

    /**
     * Get the URL for the attachment.
     * 
     * @param Attendance $attendance
     * @return string|array|null
     */
    public function getAttachmentUrl(Attendance $attendance): string|array|null;

    /**
     * Check if Face Enrollment should be enforced.
     * 
     * @return bool
     */
    public function shouldEnforceFaceEnrollment(): bool;

    /**
     * Store attendance photo securely.
     * 
     * @param string $base64Data
     * @param string $filename
     * @return string
     */
    public function storeAttendancePhoto(string $base64Data, string $filename): string;

    /**
     * Register a face descriptor for the user.
     * 
     * @param \App\Models\User $user
     * @param array $descriptor
     * @return void
     */
    public function registerFace(\App\Models\User $user, array $descriptor): void;

    /**
     * Remove the user's face registration.
     * 
     * @param \App\Models\User $user
     * @return void
     */
    public function removeFace(\App\Models\User $user): void;
}
