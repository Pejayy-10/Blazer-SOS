<?php

namespace App\Exports;

use App\Models\YearbookProfile;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Database\Eloquent\Builder;

class YearbookProfilesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithEvents
{
    protected Builder $query;
    protected $platformName;
    protected $platformYear;

    public function __construct(Builder $query)
    {
        $this->query = $query;
        // Get platform info if available to use in title
        $platform = $query->first()?->yearbookPlatform;
        $this->platformName = $platform ? $platform->name : 'All Platforms';
        $this->platformYear = $platform ? $platform->year : date('Y');
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
    
    /**
     * @return string
     */
    public function title(): string
    {
        return "Yearbook Profiles - {$this->platformName} {$this->platformYear}";
    }
    
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'] // Indigo color
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            // Style all cells
            'A1:Z1000' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB']
                    ]
                ]
            ],
            // Style even rows with a light background
            'A:Z' => [
                'alignment' => [
                    'wrapText' => true
                ]
            ]
        ];
    }
    
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto filter
                $lastColumn = $event->sheet->getHighestColumn();
                $event->sheet->setAutoFilter("A1:{$lastColumn}1");
                
                // Freeze the first row
                $event->sheet->freezePane('A2');
                
                // Add zebra striping (every other row has a light gray background)
                $highestRow = $event->sheet->getHighestRow();
                for ($row = 2; $row <= $highestRow; $row += 2) {
                    $event->sheet->getStyle('A'.$row.':'.$lastColumn.$row)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('F3F4F6'); // Light gray
                }
                
                // Add conditional formatting for payment status
                $paymentStatusColumn = 'Z'; // Adjust this based on your actual column
                $conditionalStyles = [
                    // Style for "Paid" cells
                    [
                        'conditional' => [
                            'condition' => \PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT,
                            'text' => 'Paid',
                            'style' => [
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'DCFCE7'] // Light green
                                ],
                                'font' => [
                                    'color' => ['rgb' => '166534'] // Dark green
                                ]
                            ]
                        ],
                        'range' => $paymentStatusColumn.'2:'.$paymentStatusColumn.$highestRow
                    ],
                    // Style for "Pending" cells
                    [
                        'conditional' => [
                            'condition' => \PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CONTAINSTEXT,
                            'text' => 'Pending',
                            'style' => [
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'FEF3C7'] // Light yellow
                                ],
                                'font' => [
                                    'color' => ['rgb' => '92400E'] // Dark yellow
                                ]
                            ]
                        ],
                        'range' => $paymentStatusColumn.'2:'.$paymentStatusColumn.$highestRow
                    ]
                ];
                
                foreach ($conditionalStyles as $conditionalStyle) {
                    $conditional = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
                    $conditional->setConditionType($conditionalStyle['conditional']['condition']);
                    $conditional->setText($conditionalStyle['conditional']['text']);
                    $conditional->getStyle()->applyFromArray($conditionalStyle['conditional']['style']);
                    
                    $conditionals = $event->sheet->getStyle($conditionalStyle['range'])->getConditionalStyles();
                    $conditionals[] = $conditional;
                    $event->sheet->getStyle($conditionalStyle['range'])->setConditionalStyles($conditionals);
                }
            }
        ];
    }
}