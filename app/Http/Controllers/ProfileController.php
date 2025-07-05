<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Get data untuk dropdown (jika user bisa edit)
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        
        return view('profile.show', compact('user', 'jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
    }

    public function edit()
    {
        $user = Auth::user();
        
        // Check if user can edit profile
        if (!$user->canEditProfile()) {
            return redirect()->route('profile.show')
                ->with('error', 'Anda sudah pernah mengedit profil. Edit profil hanya dapat dilakukan sekali.');
        }
        
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        
        return view('profile.edit', compact('user', 'jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Check if user can edit profile
        if (!$user->canEditProfile()) {
            return redirect()->route('profile.show')
                ->with('error', 'Anda sudah pernah mengedit profil. Edit profil hanya dapat dilakukan sekali.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(10)->format('Y-m-d'),
            'address' => 'required|string|max:500',
            'bib_name' => 'required|string|max:255',
            'jersey_size' => 'required|string|max:10',
            'race_category' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'emergency_contact_1' => 'required|string|max:255',
            'emergency_contact_2' => 'nullable|string|max:255',
            'group_community' => 'nullable|string|max:255',
            'blood_type' => 'required|string|max:5',
            'occupation' => 'required|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'event_source' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'edit_reason' => 'required|string|max:500',
        ], [
            'edit_reason.required' => 'Alasan edit profil wajib diisi.',
            'birth_date.before_or_equal' => 'Minimal umur 10 tahun.',
            'bib_name.required' => 'Nama BIB wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Siapkan data update
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'bib_name' => $request->bib_name,
            'jersey_size' => $request->jersey_size,
            'race_category' => $request->race_category,
            'whatsapp_number' => $request->whatsapp_number,
            'emergency_contact_1' => $request->emergency_contact_1,
            'emergency_contact_2' => $request->emergency_contact_2,
            'group_community' => $request->group_community,
            'blood_type' => $request->blood_type,
            'occupation' => $request->occupation,
            'medical_history' => $request->medical_history,
            'event_source' => $request->event_source,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user data
        $user->update($updateData);

        // Mark profile as edited
        $editNotes = "Profil diedit pada " . now()->format('d/m/Y H:i') . 
                    ". Alasan: " . $request->edit_reason;
        $user->markProfileAsEdited($editNotes);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui! Anda tidak dapat mengedit profil lagi.');
    }
}
