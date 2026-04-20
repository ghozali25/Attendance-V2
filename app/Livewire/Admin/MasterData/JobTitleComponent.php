<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class JobTitleComponent extends Component
{
    use InteractsWithBanner;

    public $name;
    public $job_level_id;
    public $division_id;
    
    public $deleteName = null;
    public $creating = false;
    public $editing = false;
    public $confirmingDeletion = false;
    public $selectedId = null;

    protected $rules = [
        'name' => ['required', 'string', 'max:255'], // Unique validation needs more complex logic if scoping by division, but simple global unique is fine for now or scope later.
        'job_level_id' => ['required', 'exists:job_levels,id'],
        'division_id' => ['nullable', 'exists:divisions,id'],
    ];

    public function showCreating()
    {
        $this->resetErrorBag();
        $this->reset();
        $this->creating = true;
    }

    public function create()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->validate();
        JobTitle::create([
            'name' => $this->name,
            'job_level_id' => $this->job_level_id,
            'division_id' => $this->division_id,
        ]);
        $this->creating = false;
        $this->name = null;
        $this->job_level_id = null;
        $this->division_id = null;
        $this->banner(__('Created successfully.'));
    }

    public function edit($id)
    {
        $this->resetErrorBag();
        $this->editing = true;
        $jobTitle = JobTitle::find($id);
        $this->name = $jobTitle->name;
        $this->job_level_id = $jobTitle->job_level_id;
        $this->division_id = $jobTitle->division_id;
        $this->selectedId = $id;
    }

    public function update()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $this->validate();
        $jobTitle = JobTitle::find($this->selectedId);
        $jobTitle->update([
            'name' => $this->name,
            'job_level_id' => $this->job_level_id,
            'division_id' => $this->division_id,
        ]);
        $this->editing = false;
        $this->selectedId = null;
        $this->banner(__('Updated successfully.'));
    }

    public function confirmDeletion($id, $name)
    {
        $this->deleteName = $name;
        $this->confirmingDeletion = true;
        $this->selectedId = $id;
    }

    public function delete()
    {
        if (Auth::user()->isNotAdmin) {
            return abort(403);
        }
        $jobTitle = JobTitle::find($this->selectedId);
        $jobTitle->delete();
        $this->confirmingDeletion = false;
        $this->selectedId = null;
        $this->deleteName = null;
        $this->banner(__('Deleted successfully.'));
    }

    public function render()
    {
        $jobTitles = JobTitle::with(['jobLevel', 'division'])->get();
        return view('livewire.admin.master-data.job-title', [
            'jobTitles' => $jobTitles,
            'jobLevels' => \App\Models\JobLevel::orderBy('rank')->get(),
            'divisions' => \App\Models\Division::all(),
        ]);
    }
}
