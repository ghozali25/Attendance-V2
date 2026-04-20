<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CashAdvance;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use App\Models\User;

class CashAdvanceManager extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $activeTab = 'requests'; // requests, users

    public $statusFilter = 'pending';
    public $search = '';

    public function mount()
    {
        if (\App\Helpers\Editions::payrollLocked()) {
            session()->flash('show-feature-lock', ['title' => 'Kasbon Locked', 'message' => 'Manage Kasbon is an Enterprise Feature 🔒. Please Upgrade.']);
            return redirect()->route(Auth::user()->isAdmin ? 'admin.dashboard' : 'home');
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function approve($id)
    {
        $advance = CashAdvance::find($id);
        if ($advance && $this->canManage($advance)) {
            $user = Auth::user();
            $isFinanceHead = ($user->isAdmin || $user->isSuperadmin || ($user->jobTitle?->jobLevel?->rank <= 2 && $user->division && strtolower($user->division->name) === 'finance'));

            if ($isFinanceHead) {
                // Final approval by Finance
                $advance->update([
                    'status' => 'approved',
                    'finance_approved_by' => $user->id,
                    'finance_approved_at' => now(),
                    // fallback to keep old code happy
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);

                $advance->user->notify(new \App\Notifications\CashAdvanceUpdated($advance));
                $advance->user->notify(new \App\Notifications\CashAdvanceUpdatedEmail($advance));

                $this->dispatch('banner-message', [
                    'style' => 'success',
                    'message' => 'Kasbon disetujui sepenuhnya.'
                ]);
            } else {
                // First layer approval by Division Head
                $advance->update([
                    'status' => 'pending_finance',
                    'head_approved_by' => $user->id,
                    'head_approved_at' => now(),
                ]);

                $this->dispatch('banner-message', [
                    'style' => 'success',
                    'message' => 'Kasbon disetujui, menunggu persetujuan Finance.'
                ]);
            }
        }
    }

    public function reject($id)
    {
        $advance = CashAdvance::find($id);
        if ($advance && $this->canManage($advance)) {
            $user = Auth::user();
            $isFinanceHead = ($user->isAdmin || $user->isSuperadmin || ($user->jobTitle?->jobLevel?->rank <= 2 && $user->division && strtolower($user->division->name) === 'finance'));

            $advance->update([
                'status' => 'rejected',
            ]);

            if ($isFinanceHead) {
                $advance->update([
                    'finance_approved_by' => $user->id,
                    'finance_approved_at' => now(),
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                ]);
            } else {
                $advance->update([
                    'head_approved_by' => $user->id,
                    'head_approved_at' => now(),
                ]);
            }

            $advance->user->notify(new \App\Notifications\CashAdvanceUpdated($advance));
            $advance->user->notify(new \App\Notifications\CashAdvanceUpdatedEmail($advance));

            $this->dispatch('banner-message', [
                'style' => 'success',
                'message' => 'Kasbon ditolak.'
            ]);
        }
    }

    public function delete($id)
    {
        $advance = CashAdvance::find($id);
        if ($advance && Auth::user()->isAdmin) {
            $advance->delete();
            $this->dispatch('banner-message', [
                'style' => 'success',
                'message' => 'Data Kasbon dihapus.'
            ]);
        } else {
            $this->dispatch('banner-message', [
                'style' => 'danger',
                'message' => 'Hanya Admin yang dapat menghapus data.'
            ]);
        }
    }

    protected function canManage($advance)
    {
        $user = Auth::user();
        if ($user->isAdmin || $user->isSuperadmin) return true;

        $myRank = $user->jobTitle?->jobLevel?->rank;
        $myDivisionId = $user->division_id;

        if (!$myRank || $myRank > 2) return false;

        $isFinanceHead = ($myRank <= 2 && $user->division && strtolower($user->division->name) === 'finance');

        if ($isFinanceHead) {
            // Finance head can manage if pending_finance, OR if it's from their own subordinate (e.g. pending)
            if ($advance->status === 'pending_finance') {
                return true;
            }
            if (
                $advance->user->division_id === $myDivisionId &&
                $advance->user->jobTitle?->jobLevel?->rank > $myRank
            ) {
                return true;
            }
        } else {
            // Division head can only manage their own subordinates when it's pending
            if (
                $advance->user->division_id === $myDivisionId &&
                $advance->user->jobTitle?->jobLevel?->rank > $myRank &&
                $advance->status === 'pending'
            ) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        $user = Auth::user();

        if ($this->activeTab === 'requests') {
            $query = CashAdvance::with(['user.jobTitle.jobLevel', 'user.kabupaten', 'approver', 'headApprover', 'financeApprover']);

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            if ($this->search) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            }

            if (!$user->isAdmin && !$user->isSuperadmin) {
                $myRank = $user->jobTitle?->jobLevel?->rank;
                $myDivisionId = $user->division_id;

                if ($myRank && $myRank <= 2) {
                    $isFinanceHead = ($user->division && strtolower($user->division->name) === 'finance');

                    if ($isFinanceHead) {
                        // See pending_finance globally, or any request from own division subordinates
                        $query->where(function ($q) use ($myRank, $myDivisionId) {
                            $q->where('status', 'pending_finance')
                                ->orWhereHas('user', function ($uq) use ($myRank, $myDivisionId) {
                                    $uq->where('division_id', $myDivisionId)
                                        ->whereHas('jobTitle.jobLevel', function ($lq) use ($myRank) {
                                            $lq->where('rank', '>', $myRank);
                                        });
                                });
                        });
                    } else {
                        // See only requests from own division subordinates
                        $query->whereHas('user', function ($uq) use ($myRank, $myDivisionId) {
                            $uq->where('division_id', $myDivisionId)
                                ->whereHas('jobTitle.jobLevel', function ($lq) use ($myRank) {
                                    $lq->where('rank', '>', $myRank);
                                });
                        });
                    }
                } else {
                    $query->where('id', 0);
                }
            }

            return view('livewire.finance.cash-advance-manager', [
                'advances' => $query->orderBy('created_at', 'desc')->paginate(10),
                'userGrouped' => collect()
            ])->layout('layouts.app');
        } else {
            // "users" tab (Grouped by User and Month)
            $query = User::with(['jobTitle', 'kabupaten', 'cashAdvances' => function ($q) {
                // only count approved or paid kasbons for the summary
                $q->whereIn('status', ['approved', 'paid', 'pending', 'rejected', 'pending_finance']);
            }])->whereHas('cashAdvances');

            if ($this->search) {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            if (!$user->isAdmin && !$user->isSuperadmin) {
                $myRank = $user->jobTitle?->jobLevel?->rank;
                if ($myRank && $myRank <= 2) {
                    $query->whereHas('jobTitle.jobLevel', function ($q) use ($myRank) {
                        $q->where('rank', '>', $myRank);
                    });
                } else {
                    $query->where('id', 0);
                }
            }

            return view('livewire.finance.cash-advance-manager', [
                'advances' => collect(),
                'userGrouped' => $query->paginate(10)
            ])->layout('layouts.app');
        }
    }
}
