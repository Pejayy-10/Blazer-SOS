<?php

namespace App\Livewire\Student;

use App\Models\YearbookProfile;
use App\Models\YearbookPlatform;
use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

#[Layout('components.layouts.app')]
#[Title('Edit Yearbook Profile')]
class YearbookProfileForm extends Component
{
    public ?YearbookProfile $profile = null;

    // --- Profile Form Fields ---
    #[Rule('nullable|string|max:100')]
    public $nickname = '';

    #[Rule('nullable|string|max:100')]
    public $middle_name = '';

    #[Rule('required|string|max:100')]
    public $year_and_section = '';

    #[Rule('nullable|date|before_or_equal:today')]
    public $birth_date = '';

    // --- Detailed Address Fields (NEW) ---
    #[Rule('nullable|string|max:1000')] // Allow longer for street address
    public $street_address = '';
    
    // Country & Region/City for dropdown selection
    #[Rule('nullable|integer|exists:countries,id')]
    public $country_id = null;
    #[Rule('nullable|string|max:255')]
    public $province_state = '';
    #[Rule('nullable|integer|exists:cities,id')]
    public $city_id = null;
    
    // Text fields for address that can be auto-filled
    #[Rule('nullable|string|max:255')]
    public $city = '';
    #[Rule('nullable|string|max:20')]  // Zip codes are usually shorter
    public $zip_code = '';
    #[Rule('nullable|string|max:100')]
    public $country = 'Philippines'; // Default country

    // Collections for dropdown options
    public Collection $countries;
    public Collection $cities;

    // --- End Detailed Address Fields ---

    #[Rule('nullable|string|max:50')]
    public $contact_number = '';
    #[Rule('nullable|string|max:255')]
    public $mother_name = '';
    #[Rule('nullable|string|max:255')]
    public $father_name = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_1 = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_2 = '';
    #[Rule('nullable|string|max:255')]
    public $affiliation_3 = '';
    #[Rule('nullable|string')]
    public $awards = '';
    #[Rule('nullable|string')]
    public $mantra = '';

    #[Rule('required|string|in:full_package,inclusions_only')]
    public $subscription_type = 'full_package';

    #[Rule('required|string|in:XS,S,M,L,XL,2XL,3XL')]
    public $jacket_size = 'M';

    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';

    public function getCalculatedAgeProperty(): ?int
    {
        if ($this->birth_date) {
            try { return Carbon::parse($this->birth_date)->age; }
            catch (\Exception $e) { return null; }
        }
        return null;
    }

    public function mount()
    {
        $user = Auth::user();
        $user->load('yearbookProfile');
        $this->profile = $user->yearbookProfile ?? null;

        // Initialize collections
        $this->countries = Country::orderBy('name')->get();
        $this->cities = collect();

        if ($this->profile) {
            $this->nickname = $this->profile->nickname;
            $this->middle_name = $this->profile->middle_name;
            $this->year_and_section = $this->profile->year_and_section;
            $this->birth_date = $this->profile->birth_date ? $this->profile->birth_date->format('Y-m-d') : '';

            // Load detailed address fields
            $this->street_address = $this->profile->street_address;
            
            // Try to find a country match
            $this->country = $this->profile->country ?? 'Philippines';
            $country = Country::where('name', $this->country)->first();
            if ($country) {
                $this->country_id = $country->id;
                $this->cities = City::where('country_id', $country->id)->orderBy('name')->get();
                
                // Try to find a city match
                $city = City::where('country_id', $country->id)
                            ->where('name', $this->profile->city)
                            ->first();
                if ($city) {
                    $this->city_id = $city->id;
                }
            }
            
            $this->city = $this->profile->city;
            $this->province_state = $this->profile->province_state;
            $this->zip_code = $this->profile->zip_code;
            
            $this->contact_number = $this->profile->contact_number;
            $this->mother_name = $this->profile->mother_name;
            $this->father_name = $this->profile->father_name;
            $this->affiliation_1 = $this->profile->affiliation_1;
            $this->affiliation_2 = $this->profile->affiliation_2;
            $this->affiliation_3 = $this->profile->affiliation_3;
            $this->awards = $this->profile->awards;
            $this->mantra = $this->profile->mantra;
            $this->subscription_type = $this->profile->subscription_type ?? 'full_package';
            $this->jacket_size = $this->profile->jacket_size ?? 'M';
        } else {
            // Default country to Philippines for new profiles
            $philippines = Country::where('name', 'Philippines')->first();
            if ($philippines) {
                $this->country_id = $philippines->id;
                $this->cities = City::where('country_id', $philippines->id)->orderBy('name')->get();
            }
        }

        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
    }

    public function updateCities()
    {
        $this->cities = collect();
        $this->city_id = null;
        $this->city = '';
        $this->zip_code = '';
        
        if ($this->country_id) {
            $country = Country::find($this->country_id);
            if ($country) {
                $this->country = $country->name;
                $this->cities = City::where('country_id', $this->country_id)
                                    ->where('state_province', $this->province_state)
                                    ->orderBy('name')
                                    ->get();
                                    
                // If there are no cities for the selected province/state, get all cities in the country
                if ($this->cities->isEmpty() && !empty($this->province_state)) {
                    $this->cities = City::where('country_id', $this->country_id)
                                        ->orderBy('name')
                                        ->get();
                }
            }
        }
    }
    
    public function searchAddressViaAPI()
    {
        if (empty($this->city) || empty($this->province_state) || empty($this->country)) {
            return;
        }
        
        $query = urlencode("{$this->city}, {$this->province_state}, {$this->country}");
        
        try {
            // Using Nominatim OSM API (free, no API key required for low usage)
            $url = "https://nominatim.openstreetmap.org/search?format=json&q={$query}&limit=1&addressdetails=1";
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Blazer SOS App'); // Nominatim requires a user agent
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response);
            
            if (!empty($data) && is_array($data) && isset($data[0])) {
                $result = $data[0];
                
                if (isset($result->address)) {
                    $this->zip_code = $result->address->postcode ?? $this->zip_code;
                    
                    // Additional data you might want to use
                    // $this->city = $result->address->city ?? $result->address->town ?? $result->address->village ?? $this->city;
                    // $this->province_state = $result->address->state ?? $this->province_state;
                    // $this->country = $result->address->country ?? $this->country;
                    
                    session()->flash('message', 'Address information found and added');
                }
            }
        } catch (\Exception $e) {
            // Silently fail - we don't want to disrupt the form if the API fails
        }
    }
    
    public function updateCityInfo()
    {
        if ($this->city_id) {
            $city = City::find($this->city_id);
            if ($city) {
                $this->city = $city->name;
                $this->zip_code = $city->postal_code;
                $this->searchAddressViaAPI();
            }
        }
    }
    
    public function save()
    {
        $validatedData = $this->validate();
        $activePlatform = YearbookPlatform::active();
        if (!$activePlatform) {
            session()->flash('error', 'No active yearbook platform found. Cannot save profile.');
            return;
        }

        $ageToStore = null;
        if (!empty($validatedData['birth_date'])) {
            try { $ageToStore = Carbon::parse($validatedData['birth_date'])->age; }
            catch (\Exception $e) {}
        }

        $profileData = [
            'yearbook_platform_id' => $activePlatform->id,
            'nickname' => $validatedData['nickname'],
            'middle_name' => $validatedData['middle_name'],
            'year_and_section' => $validatedData['year_and_section'],
            'age' => $ageToStore,
            'birth_date' => $validatedData['birth_date'] ?: null,

            // Save detailed address fields
            'street_address' => $validatedData['street_address'],
            'city' => $validatedData['city'],
            'province_state' => $validatedData['province_state'],
            'zip_code' => $validatedData['zip_code'],
            'country' => $validatedData['country'],

            'contact_number' => $validatedData['contact_number'],
            'mother_name' => $validatedData['mother_name'],
            'father_name' => $validatedData['father_name'],
            'affiliation_1' => $validatedData['affiliation_1'],
            'affiliation_2' => $validatedData['affiliation_2'],
            'affiliation_3' => $validatedData['affiliation_3'],
            'awards' => $validatedData['awards'],
            'mantra' => $validatedData['mantra'],
            'subscription_type' => $validatedData['subscription_type'],
            'jacket_size' => $validatedData['jacket_size'],
            'profile_submitted' => true,
            'submitted_at' => $this->profile?->submitted_at ?? now(),
        ];

        Auth::user()->yearbookProfile()->updateOrCreate(
            ['user_id' => Auth::id()],
            $profileData
        );
        session()->flash('message', 'Yearbook profile information saved successfully!');
    }

    public function render()
    {
        return view('livewire.student.yearbook-profile-form', [
            'countries' => $this->countries,
            'cities' => $this->cities,
        ]);
    }
}