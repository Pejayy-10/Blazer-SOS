<?php

namespace App\Exports;

use App\Models\YearbookProfile;
use Maatwebsite\Excel\Concerns\FromQuery; // Use FromQuery for efficiency with large datasets
use Maatwebsite\Excel\Concerns\WithHeadings; // To define column headers
use Maatwebsite\Excel\Concerns\WithMapping; // To map/format data for each row
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // To automatically adjust column widths
use Illuminate\Database\Eloquent\Builder; // Type hint for the query builder

class YearbookProfilesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * The Eloquent query builder instance containing the filtered data.
     * @var Builder
     */
    protected Builder $query;

    /**
     * Constructor to accept the filtered query from the Livewire component.
     *
     * @param Builder $query The pre-filtered Eloquent query.
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Return the Eloquent query that retrieves the data for the export.
     * Ensures necessary relationships are loaded.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        // Return the pre-filtered query passed from the component.
        // Eager loading should ideally happen in the component's buildFilteredQuery method,
        // but we ensure it here as well for robustness.
        return $this->query->with(['user', 'college', 'course', 'major', 'yearbookPlatform']);
    }

    /**
     * Define the headings (column names) for the export file.
     *
     * @return array<int, string>
     */
    public function headings(): array
    {
        // Define your desired column headers in order
        return [
            'User ID',
            'Username',
            'First Name',
            'Last Name',
            'Email',
            'Platform Year',        // <-- New Heading
            'Platform Name',        // <-- New Heading
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
            // Add other relevant fields from YearbookProfile or User if needed
        ];
    }

    /**
     * Map the data from each model instance to an array for the export row.
     * The order must match the order defined in headings().
     *
     * @param mixed $profile Technically an object, often type-hinted as the Model (YearbookProfile)
     * @return array<int, mixed>
     */
    public function map($profile): array
    {
         // Ensure $profile is treated as the correct type for easier access
         /** @var \App\Models\YearbookProfile $profile */

         // Access data using the $profile object and its loaded relationships
         // Use null coalescing operator (??) to provide default values if relationships are missing
        return [
            $profile->user_id,
            $profile->user?->username ?? 'N/A',
            $profile->user?->first_name ?? 'N/A',
            $profile->user?->last_name ?? 'N/A',
            $profile->user?->email ?? 'N/A',
            $profile->yearbookPlatform?->year ?? 'N/A',     // <-- New Data Point
            $profile->yearbookPlatform?->name ?? 'N/A',     // <-- New Data Point
            $profile->nickname,
            $profile->college?->name ?? 'N/A', // Example: Accessing related College name
            $profile->course?->name ?? 'N/A', // Example: Accessing related Course name
            $profile->major?->name ?? 'N/A',  // Example: Accessing related Major name
            $profile->year_and_section,
            $profile->age,
            $profile->birth_date ? $profile->birth_date->format('Y-m-d') : null, // Format dates
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
            ucfirst($profile->payment_status ?? 'N/A'), // Capitalize status
            $profile->submitted_at ? $profile->submitted_at->format('Y-m-d H:i:s') : null, // Format timestamps
            $profile->paid_at ? $profile->paid_at->format('Y-m-d H:i:s') : null, // Format timestamps
        ];
    }
}