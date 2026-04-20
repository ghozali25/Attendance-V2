<?php

namespace App\Imports;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function __construct(public bool $save = true)
    {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // ... existing logic ...
        $division_id = Division::where('name', $row['division'])->first()?->id
            ?? Division::create(['name' => $row['division']])?->id;
        $job_title_id = JobTitle::where('name', $row['job_title'])->first()?->id
            ?? JobTitle::create(['name' => $row['job_title']])?->id;
        $education_id = Education::where('name', $row['education'])->first()?->id
            ?? Education::create(['name' => $row['education']])?->id;
        $user = (new User)->forceFill([
            'id' => isset($row['id']) ? $row['id'] : null,
            'nip' => (string) $row['nip'],
            'name' => $row['name'],
            'email' => $row['email'],
            'group' => $row['group'] ?? 'user',
            'phone' => (string) $row['phone'],
            'gender' => $row['gender'],
            'basic_salary' => $row['basic_salary'] ?? 0,
            'hourly_rate' => $row['hourly_rate'] ?? 0,
            'birth_date' => $row['birth_date'],
            'birth_place' => $row['birth_place'],
            'address' => $row['address'],
            'city' => $row['city'],
            'education_id' => $education_id,
            'division_id' => $division_id,
            'job_title_id' => $job_title_id,
            'password' => Hash::make($row['password']),
            'created_at' => isset($row['created_at']) ? $row['created_at'] : \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        
        if ($this->save) {
            $user->save();
            \Illuminate\Support\Facades\Log::info('User imported successfully: ' . $user->email);
        }
        return $user;
    }

    public function rules(): array
    {
        return [
            'nip' => ['required', Rule::unique('users', 'nip')],
            'name' => ['required', 'string'],
            'email' => ['required', 'string', Rule::unique('users', 'email')],
            'phone' => ['nullable', Rule::unique('users', 'phone')],
            'gender' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }
}
