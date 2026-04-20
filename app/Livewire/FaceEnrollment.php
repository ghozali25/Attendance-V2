<?php

namespace App\Livewire;

use App\Models\FaceDescriptor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FaceEnrollment extends Component
{
    public bool $isEnrolled = false;
    public bool $isCapturing = false;

    public function mount()
    {
        if (\App\Helpers\Editions::attendanceLocked()) {
             session()->flash('show-feature-lock', ['title' => 'Face ID Locked', 'message' => 'Face ID Biometrics is an Enterprise Feature 🔒. Please Upgrade.']);
             return redirect()->route('home');
        }

        $this->isEnrolled = Auth::user()->hasFaceRegistered();
    }

    /**
     * Save the face descriptor from the frontend.
     */
    public function saveFaceDescriptor($descriptor)
    {
        if (!$descriptor) return;
        
        $user = Auth::user();

        // Validate descriptor length (face-api.js returns 128-dimension array)
        if (count($descriptor) !== 128) {
            $this->dispatch('toast', type: 'error', message: __('Invalid face data. Please try again.'));
            return;
        }

        try {
            app(\App\Contracts\AttendanceServiceInterface::class)->registerFace($user, $descriptor);
            
            $this->isEnrolled = true;
            $this->isCapturing = false;
    
            $this->dispatch('toast', type: 'success', message: __('Face ID registered successfully!'));
            $this->dispatch('face-enrolled');
        } catch (\Exception $e) {
            if ($e->getCode() == 403) {
                $this->dispatch('feature-lock', title: 'Face ID Locked', message: $e->getMessage());
            } else {
                throw $e;
            }
        }
    }

    /**
     * Remove the user's face registration.
     */
    public function removeFace()
    {
        try {
            app(\App\Contracts\AttendanceServiceInterface::class)->removeFace(Auth::user());
            
            $this->isEnrolled = false;
            $this->dispatch('toast', type: 'success', message: __('Face ID removed.'));
        } catch (\Exception $e) {
            if ($e->getCode() == 403) {
                $this->dispatch('feature-lock', title: 'Face ID Locked', message: $e->getMessage());
            } else {
                throw $e;
            }
        }
    }

    /**
     * Start the capture process.
     */
    public function startCapture()
    {
        $this->isCapturing = true;
    }

    /**
     * Cancel the capture process.
     */
    public function cancelCapture()
    {
        $this->isCapturing = false;
    }

    public function render()
    {
        return view('livewire.face-enrollment')->layout('layouts.app');
    }
}
