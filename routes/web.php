<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Import Auth facade

// Import Livewire Components
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Student\YearbookProfileForm;
use App\Livewire\Admin\ManageSubscriptions;
use App\Livewire\Superadmin\ManageRoleNames;
use App\Livewire\Superadmin\ManageStaff;
use App\Livewire\Auth\RegisterAdmin;
use App\Livewire\Admin\ViewSubscriptionDetails;
use App\Livewire\Student\ManagePhotos;
use App\Livewire\Student\AcademicArea;
use App\Livewire\Admin\ManageAcademicStructure;
use App\Livewire\Admin\PlatformSetup;
use App\Livewire\Student\SubscriptionStatus;
use App\Livewire\Admin\YearbookRepository;
use App\Livewire\Admin\ManageYearbookPlatforms;
use App\Livewire\UserProfile\UpdateAccountInformation;
use App\Livewire\UserProfile\UpdateProfileInformation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Guest Routes ---
// Routes accessible only when the user is NOT logged in
Route::middleware('guest')->group(function () { // <<< START Guest group
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register'); // For regular students

    // --- Admin Registration Route (MOVED HERE) ---
    Route::get('/register/admin/{token}', RegisterAdmin::class)
        ->name('admin.register.form');

    // Add password reset/email verification routes here if needed later

}); // <<< END Guest group


// --- Authenticated Routes ---
// Routes accessible only when the user IS logged in
Route::middleware('auth')->group(function () {

    // -- Routes accessible by ANY authenticated user --
    Route::get('/app', Dashboard::class)->name('app.dashboard');

    // Account Settings Route (NEW)
    Route::get('/user/account', UpdateAccountInformation::class)->name('user.account.settings');

    // Profile Settings Route (NEW)
    Route::get('/user/profile', UpdateProfileInformation::class)->name('user.profile');

    // Standard Laravel Logout Route (POST request)
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');


    // -- Student Routes --
    Route::get('/student/yearbook-profile/edit', YearbookProfileForm::class)->name('student.profile.edit');
    Route::get('/student/photos', ManagePhotos::class)->name('student.photos'); // <-- Add this route
    Route::get('/student/academic', AcademicArea::class)->name('student.academic'); // <-- Add this route
    Route::get('/student/subscription', SubscriptionStatus::class)->name('student.subscription.status');


    // -- Routes accessible by ADMINS and SUPERADMINS --
    Route::middleware('role:admin,superadmin')->group(function () {
    Route::get('/admin/subscriptions', ManageSubscriptions::class)->name('admin.subscriptions.index');

    // View Subscription Details Route (Added)
    // Using route model binding for convenience
    Route::get('/admin/subscriptions/{profile}', ViewSubscriptionDetails::class)->name('admin.subscriptions.show');
    Route::get('/admin/yearbook-platforms', ManageYearbookPlatforms::class)->name('admin.platforms.index');
    Route::get('/admin/repository', YearbookRepository::class)->name('admin.repository.index');
    });

    // Academic Structure Route (Update to point to correct component)
    Route::get('/admin/academic-structure', ManageAcademicStructure::class)->name('admin.academic-structure.index'); // Changed name slightly for consistency


    // -- Routes accessible ONLY by SUPERADMINS --
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/role-names', ManageRoleNames::class)->name('superadmin.roles.index');
        Route::get('/superadmin/staff', ManageStaff::class)->name('superadmin.staff.index');
    });

    // REMOVED Admin registration route from here

});


// --- Root URL Redirect Logic ---
Route::get('/', function () {
    if (Auth::check()) { // Use Laravel's built-in check
        return redirect()->route('app.dashboard');
    }
    return redirect()->route('login');
});