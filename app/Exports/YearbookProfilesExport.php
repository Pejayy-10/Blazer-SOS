<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Models\YearbookProfile;
use Illuminate\Database\Eloquent\Builder;

class YearbookProfilesExport implements WithHeadings, WithMapping, ShouldAutoSize, FromQuery
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query->with([
            'user:id,username,first_name,last_name,email',
            'college:id,name',
            'course:id,name',   
            'major:id,name'
        ]);
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Username',
            'First Name',
            'Last Name',
            'Email',
            'Nickname',
            'College',
            'Course',
            'Major',
            'Year & Section',
            'Age',
            'Birth Date',
            'Address',
            'Contact Number',
            'Mother Name',
            'Father Name',
            'Affiliation 1',
            'Affiliation 2',
            'Affiliation 3',
            'Awards',
            'Mantra',
            'Subscription Type',
            'Jacket Size',
            'Payment Status',
            'Profile Submitted At',
            'Payment Confirmed At',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->user_id,
            $profile->user->username ?? 'N/A',
            $profile->user->first_name ?? 'N/A',
            $profile->user->last_name ?? 'N/A',
            $profile->user->email ?? 'N/A',
            $profile->nickname,
            $profile->college->name ?? 'N/A',
            $profile->course->name ?? 'N/A',
            $profile->major->name ?? 'N/A',
            $profile->year_and_section,
            $profile->age,
            $profile->birth_date?->format('Y-m-d'),
            $profile->address,
            $profile->contact_number,
            $profile->mother_name,
            $profile->father_name,
            $profile->affiliation_1,
            $profile->affiliation_2,
            $profile->affiliation_3,
            $profile->awards,
            $profile->mantra,
            $profile->subscription_type,
            $profile->jacket_size,
            $profile->payment_status,
            $profile->submitted_at?->format('Y-m-d H:i:s'),
            $profile->paid_at?->format('Y-m-d H:i:s'),
        ];
    }
}