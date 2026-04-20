<?php

namespace App\Livewire\Admin\ImportExport;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User as UserModel;
use Livewire\WithFileUploads;

class User extends Component
{
    use InteractsWithBanner, WithFileUploads;

    public function mount()
    {
        if (\App\Helpers\Editions::reportingLocked()) {
             session()->flash('show-feature-lock', ['title' => 'Import/Export Locked', 'message' => 'User Import/Export is an Enterprise Feature 🔒. Please Upgrade.']);
             return redirect()->route('admin.dashboard');
        }
    }

    public bool $previewing = false;
    public ?string $mode = null;
    public $groups = [];
    public $file = null;
    public $importErrors = [];

    protected $rules = [
        'file' => 'required|mimes:csv,xls,xlsx,ods'
    ];

    public function preview()
    {
        $this->previewing = !$this->previewing;
        $this->mode = $this->previewing ? 'export' : null;
    }

    public function updated()
    {
        $this->validateGroups();
    }

    public function render()
    {
        $users = null;
        if ($this->file) {
            $this->mode = 'import';
            $this->previewing = true;
            $userImport = new UsersImport(save: false);
            $users = Excel::toCollection($userImport, $this->file)
                ->first()
                ->map(function (\Illuminate\Support\Collection $v) use ($userImport) {
                    return $userImport->model($v->toArray());
                });
        } else if ($this->previewing && $this->mode == 'export') {
            $users = empty($this->groups) ?
                new \Illuminate\Support\Collection :
                UserModel::whereIn('group', $this->groups)->get();
        } else {
            $this->previewing = false;
            $this->mode = null;
        }
        return view('livewire.admin.import-export.user', [
            'users' => $users
        ]);
    }

    public function import()
    {
        \Illuminate\Support\Facades\Log::info('Import method triggered');

        if (Auth::user()->isNotAdmin) {
            \Illuminate\Support\Facades\Log::warning('User is not admin');
            abort(403);
        }

        if (\App\Helpers\Editions::reportingLocked()) {
             \Illuminate\Support\Facades\Log::info('Import locked by edition');
             $this->dispatch('feature-lock', title: 'Import Locked', message: 'Importing Users is an Enterprise Feature 🔒. Please Upgrade.');
             return;
        }
        try {
            \Illuminate\Support\Facades\Log::info('Validating file');
            $this->validate();
            \Illuminate\Support\Facades\Log::info('File validated', ['file' => $this->file ? $this->file->getClientOriginalName() : 'null']);

            $import = new UsersImport;
            Excel::import($import, $this->file);
            \Illuminate\Support\Facades\Log::info('Excel import executed');

            $failures = $import->failures();
            
            if ($failures->isNotEmpty()) {
                $this->importErrors = [];
                foreach ($failures as $failure) {
                    $this->importErrors[] = [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ];
                    \Illuminate\Support\Facades\Log::warning('Row ' . $failure->row() . ' failed: ' . implode(', ', $failure->errors()));
                }
                $this->dangerBanner(__('Import completed with some errors. Please check the list below.'));
            } else {
                $this->banner(__('Success! All users imported correctly.'));
                $this->importErrors = [];
            }
            
            $this->reset('file'); // Keep file reset but maybe keep errors visible
            $this->dispatch('refresh-navigation'); 
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('Import failed: ' . $th->getMessage());
            $this->dangerBanner($th->getMessage());
        }
    }

    public function export()
    {
        if (Auth::user()->isNotAdmin) {
            abort(403);
        }

        if (\App\Helpers\Editions::reportingLocked()) {
            $this->dispatch('feature-lock', title: 'Export Locked', message: 'Exporting Users is an Enterprise Feature 🔒. Please Upgrade.');
            return;
        }

        $this->validateGroups();
        return Excel::download(
            new UsersExport($this->groups),
            'users.xlsx'
        );
    }

    public function downloadTemplate()
    {
         if (\App\Helpers\Editions::reportingLocked()) {
            $this->dispatch('feature-lock', title: 'Export Locked', message: 'Downloading Template is an Enterprise Feature 🔒. Please Upgrade.');
            return;
        }

        return Excel::download(
            new \App\Exports\UsersTemplateExport,
            'user_import_template.xlsx'
        );
    }

    private function validateGroups()
    {
        $this->validate([
            'groups.*' => ['string', 'in:user,admin,superadmin'],
            'groups' => ['required', 'array']
        ]);
    }
}
