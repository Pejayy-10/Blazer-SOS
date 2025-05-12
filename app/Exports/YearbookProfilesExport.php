<?php

namespace App\Exports;

use App\Models\YearbookProfile;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Eloquent\Builder;

class YearbookProfilesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /** @return Builder */
    public function query(): Builder
    {
        return $this->query->with(['user', 'college', 'course', 'major', 'yearbookPlatform']);
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return [
            'User ID', 'Username', 'First Name', 'Last Name', 'Email',
            'Platform Year', 'Platform Name', 'Nickname', 'College', 'Course',
            'Major', 'Year & Section', 'Age', 'Birth Date', 'Address',
            'Contact Number', 'Mother Name', 'Father Name', 'Affiliation 1',
            'Affiliation 2', 'Affiliation 3', 'Awards', 'Mantra',
            'Package Type', 
            'Jacket Size', 'Payment Status', 'Profile Submitted At', 'Payment Confirmed At',
        ];
    }

    /**
     * @param mixed $profile
     * @return array<int, mixed>
     */
    public function map($profile): array
    {
         /** @var \App\Models\YearbookProfile $profile */

         $packageTypeLabel = match ($profile->subscription_type) {
             'full_package' => 'Full Package',
             'inclusions_only' => 'Inclusions Only',
             default => $profile->subscription_type ?? 'N/A',
         };

        return [
            $profile->user_id, $profile->user?->username ?? 'N/A', $profile->user?->first_name ?? 'N/A',
            $profile->user?->last_name ?? 'N/A', $profile->user?->email ?? 'N/A',
            $profile->yearbookPlatform?->year ?? 'N/A', $profile->yearbookPlatform?->name ?? 'N/A',
            $profile->nickname, $profile->college?->name ?? 'N/A', $profile->course?->name ?? 'N/A',
            $profile->major?->name ?? 'N/A', $profile->year_and_section, $profile->age,
            $profile->birth_date ? $profile->birth_date->format('Y-m-d') : null, $profile->address,
            $profile->contact_number, $profile->mother_name, $profile->father_name,
            $profile->affiliation_1, $profile->affiliation_2, $profile->affiliation_3,
            $profile->awards, $profile->mantra,
            $packageTypeLabel, 
            $profile->jacket_size, ucfirst($profile->payment_status ?? 'N/A'),
            $profile->submitted_at ? $profile->submitted_at->format('Y-m-d H:i:s') : null,
            $profile->paid_at ? $profile->paid_at->format('Y-m-d H:i:s') : null,
        ];
    }
}