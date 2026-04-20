<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SystemMaintenance extends Component
{
    use \Livewire\WithFileUploads;

    public $cleanAttendances = false;
    public $cleanActivityLogs = false;
    public $cleanNotifications = false;
    public $cleanStorage = false;
    public $cleanNonAdminUsers = false;

    public $backupFile;

    // ... existing properties ...

    public function restoreDatabase()
    {
        if (!Auth::user()->isSuperadmin || Auth::user()->is_demo) {
            $this->dispatch('error', message: __('Unauthorized action.'));
            return;
        }

        $this->validate([
            'backupFile' => 'required|file|max:51200', // 50MB Max
        ]);

        try {
            $path = $this->backupFile->getRealPath();
            
            // Validate extension manually strictly
            $extension = $this->backupFile->getClientOriginalExtension();
            if (strtolower($extension) !== 'sql') {
                $this->dispatch('error', message: __('Invalid file type. Only .sql files are allowed.'));
                return;
            }

            // PURE PHP RESTORE (Shared Hosting Safe)
            // We use DB::unprepared to natively execute the SQL payload via PDO.
            // This avoids process/shell requirements entirely.
            $sqlContents = file_get_contents($path);
            
            if (empty(trim($sqlContents))) {
                throw new \Exception("Backup file is empty.");
            }

            // Disable foreign keys temporarily during mass-restore
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sqlContents);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->dispatch('success', message: __('Database restored successfully! The page will reload.'));
            
            // Delay reload to let toast show
            $this->js("setTimeout(function(){ window.location.reload(); }, 2000);");

        } catch (\Exception $e) {
            $this->dispatch('error', message: __('Restore failed: ') . $e->getMessage());
        }
    }

    public function cleanDatabase()
    {
        if (!Auth::user()->isSuperadmin || Auth::user()->is_demo) {
            $this->dispatch('error', message: __('Unauthorized action.'));
            return;
        }

        if (!$this->cleanAttendances && !$this->cleanActivityLogs && !$this->cleanNonAdminUsers && !$this->cleanNotifications && !$this->cleanStorage) {
            $this->dispatch('warning', message: __('Please select at least one option to clean.'));
            return;
        }

        try {
            // Disable Foreign Key Checks to allow truncation
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');   
            
            if ($this->cleanAttendances) {
                Attendance::truncate();
            }

            if ($this->cleanActivityLogs) {
                DB::table('activity_logs')->truncate();
            }

            if ($this->cleanNotifications) {
                DB::table('notifications')->truncate();
            }

            if ($this->cleanNonAdminUsers) {
                // Delete users who are NOT admin or superadmin
                User::where('group', 'user')->delete();
            }

            // Re-enable Foreign Key Checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            if ($this->cleanStorage) {
                // Delete physical files
                Storage::disk('public')->deleteDirectory('attendance-photos');
                Storage::disk('public')->deleteDirectory('attachments');
                // Recreate empty directories to prevent listing errors if any
                Storage::disk('public')->makeDirectory('attendance-photos');
                Storage::disk('public')->makeDirectory('attachments');
            }

            $this->dispatch('success', message: __('Selected data and files cleaned successfully.'));
            
            // Reset checkboxes
            $this->cleanAttendances = false;
            $this->cleanActivityLogs = false;
            $this->cleanNotifications = false;
            $this->cleanStorage = false;
            $this->cleanNonAdminUsers = false;

        } catch (\Exception $e) {
            $this->dispatch('error', message: __('Failed to clean database: ') . $e->getMessage());
        }
    }

    private function generateSqlDumpNative($path)
    {
        $handle = fopen($path, 'w');
        if (!$handle) {
            throw new \Exception("Cannot open file for writing at {$path}");
        }
        
        fwrite($handle, "-- Absensi GPS & Enterprise Database Backup\n");
        fwrite($handle, "-- Generated at: " . now()->toDateTimeString() . "\n\n");
        fwrite($handle, "SET sql_mode = '';\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $tableInfo) {
            $tableArray = (array)$tableInfo;
            $table = array_values($tableArray)[0];

            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            $createArray = (array)$createTable[0];
            
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createArray['Create Table'] . ";\n\n");

            // Fetch data incrementally via cursor to save memory limits
            foreach (DB::table($table)->cursor() as $row) {
                $rowArray = (array)$row;
                $values = array_map(function ($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }
                    $value = addslashes($value);
                    // sanitize new lines
                    $value = str_replace("\n", "\\n", $value);
                    $value = str_replace("\r", "\\r", $value);
                    return "'" . $value . "'";
                }, array_values($rowArray));

                fwrite($handle, "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n");
            }
            fwrite($handle, "\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    public function downloadBackup()
    {
        if (!Auth::user()->isSuperadmin || Auth::user()->is_demo) {
            $this->dispatch('error', message: __('Unauthorized action.'));
            return;
        }

        try {
            $filename = 'backup-native-' . date('Y-m-d-H-i-s') . '.sql';
            $path = storage_path('app/' . $filename);

            // Execute Custom Native PHP Database Dump
            $this->generateSqlDumpNative($path);

            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            $this->dispatch('error', message: __('Backup failed: ') . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.system-maintenance')
            ->layout('layouts.app');
    }
}
