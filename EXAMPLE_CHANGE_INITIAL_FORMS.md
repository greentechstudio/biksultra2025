// ================================
// CONTOH PERUBAHAN: FORM AWAL 5 PESERTA
// ================================

// 1. File: public/js/collective-registration.js

// SEBELUM (Line 58):
function generateInitialForms() {
    // Generate 10 initial forms
    for (let i = 0; i < 10; i++) {
        addParticipantForm();
    }
}

// SESUDAH:
function generateInitialForms() {
    // Generate 5 initial forms
    for (let i = 0; i < 5; i++) {
        addParticipantForm();
    }
}

// ================================

// SEBELUM (Line 462):
function removeParticipantForm(index) {
    // Check if we have minimum 10 participants
    if (participantCount <= 10) {
        showWarningMessage('Minimal 10 peserta diperlukan untuk registrasi kolektif. Form tidak dapat dihapus.');
        return;
    }
}

// SESUDAH:
function removeParticipantForm(index) {
    // Check if we have minimum 5 participants
    if (participantCount <= 5) {
        showWarningMessage('Minimal 5 peserta diperlukan untuk registrasi kolektif. Form tidak dapat dihapus.');
        return;
    }
}

// ================================
// 2. File: app/Http/Controllers/AuthController.php

// SEBELUM (Line 2149):
if ($validParticipants < 10) {
    return redirect()->back()
        ->withErrors(['participants' => "Registrasi kolektif minimal harus ada 10 peserta. Saat ini hanya {$validParticipants} peserta yang lengkap."])
        ->withInput();
}

// SESUDAH:
if ($validParticipants < 5) {
    return redirect()->back()
        ->withErrors(['participants' => "Registrasi kolektif minimal harus ada 5 peserta. Saat ini hanya {$validParticipants} peserta yang lengkap."])
        ->withInput();
}

// ================================
// HASIL AKHIR:
// ✅ Form awal: 5 peserta
// ✅ Minimum: 5 peserta 
// ✅ Maximum: 50 peserta (tidak berubah)
// ✅ User bisa tambah form sampai maksimal 50
// ✅ User tidak bisa hapus form jika tinggal 5
