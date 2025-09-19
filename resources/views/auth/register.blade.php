@extends('layouts.guest')

@section('title', 'Register - Event Lari')

@section('content')
<style>
/* Import Modern Typography */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');

/* Modern Minimalist Design System */
:root {
    --primary-color: #2563eb;
    --primary-hover: #1d4ed8;
    --primary-light: #dbeafe;
    --secondary-color: #64748b;
    --text-dark: #0f172a;
    --text-medium: #475569;
    --text-light: #64748b;
    --background: #ffffff;
    --surface: #f8fafc;
    --border: #e2e8f0;
    --border-focus: #3b82f6;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --radius-sm: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
}

/* Base Typography */
* {
    font-family: 'Inter', 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* Main Container */
.registration-container {
    min-height: 100vh;
    display: flex;
    background: var(--surface);
}

/* Hero Section (Left Side) */
.hero-section {
    flex: 0.5;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: white;
    overflow: hidden;
}

.hero-content {
    text-align: center;
    max-width: 500px;
    z-index: 2;
    position: relative;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    font-weight: 400;
    line-height: 1.6;
}

.hero-features {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 2rem;
}

.hero-feature {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius-sm);
    backdrop-filter: blur(10px);
}

.hero-feature-icon {
    width: 2rem;
    height: 2rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Background Pattern */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    z-index: 1;
}

/* Form Section (Right Side) */
.form-section-container {
    flex: 1;
    background: var(--background);
    overflow-y: auto;
    max-height: 100vh;
    display: flex;
    align-items: stretch;
}

/* Form Container */
.form-container {
    max-width: 700px;
    margin: 0 auto;
    padding: 3rem 2.5rem;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

/* Form Header - Top Section */
.form-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2.5rem;
    border-bottom: 2px solid var(--border);
    flex-shrink: 0;
}

.form-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: var(--text-dark);
    letter-spacing: -0.025em;
    line-height: 1.2;
}

.form-header p {
    font-size: 1.125rem;
    color: var(--text-medium);
    font-weight: 400;
    line-height: 1.5;
}

/* Form Content - Middle Section */
#registrationForm {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Form Sections Container */
.form-sections-container {
    flex: 1;
    margin-bottom: 2rem;
}

/* Submit Section - Bottom Section */
.submit-section {
    flex-shrink: 0;
    margin-top: auto;
    padding-top: 3rem;
    border-top: 2px solid var(--border);
}

/* Submit Button */
.submit-button {
    width: 100%;
    padding: 1.25rem 2.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.submit-button:hover {
    background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.submit-button:active {
    transform: translateY(0);
}

/* Login Link */
.login-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.login-link p {
    color: var(--text-medium);
    font-size: 0.95rem;
    margin: 0;
}

.login-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.login-link a:hover {
    color: var(--primary-hover);
    text-decoration: underline;
}

/* Form Sections */
.form-section {
    background: var(--background);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
    border: 2px solid var(--border);
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease forwards;
}

.form-section:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--border-focus);
    transform: translateY(-2px);
}

/* Animation for form sections */
@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.section-title {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--border);
}

.section-icon {
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1.5rem;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.section-title h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    letter-spacing: -0.025em;
}

/* Input Styling */
.input-group {
    margin-bottom: 2rem;
}

.input-label {
    display: block;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
    letter-spacing: -0.01em;
}

.required-mark {
    color: #dc2626;
    margin-left: 0.25rem;
    font-weight: 700;
}

.modern-input {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--background);
    font-size: 1rem;
    color: var(--text-dark);
    transition: all 0.3s ease;
    outline: none;
    font-weight: 400;
}

.modern-input:hover {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
}

.modern-input:focus {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 4px rgb(59 130 246 / 0.15);
    transform: translateY(-1px);
}

.modern-input::placeholder {
    color: var(--text-light);
    font-weight: 400;
}

/* Select Styling */
.modern-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 1rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 3rem;
    appearance: none;
}

/* Textarea Styling */
.modern-textarea {
    resize: vertical;
    min-height: 6rem;
    font-family: inherit;
}

/* Grid Layout */
.form-grid {
    display: grid;
    gap: 2rem;
    width: 100%;
}

.form-grid-2 {
    grid-template-columns: 1fr;
}

@media (min-width: 768px) {
    .form-grid-2 {
        grid-template-columns: 1fr 1fr;
    }
}

/* Input Group Styling */
.input-group {
    margin-bottom: 2rem;
    width: 100%;
}

.input-group:last-child {
    margin-bottom: 0;
}

/* Phone Input Group */
.phone-input-group {
    display: flex;
    border: 2px solid var(--border);
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
    overflow: hidden;
}

.phone-input-group:hover {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
}

.phone-input-group:focus-within {
    border-color: var(--border-focus);
    box-shadow: 0 0 0 4px rgb(59 130 246 / 0.15);
}

.phone-prefix {
    background: var(--surface);
    padding: 1rem 1.25rem;
    border-right: 2px solid var(--border);
    font-weight: 600;
    color: var(--text-medium);
    white-space: nowrap;
    display: flex;
    align-items: center;
}

.phone-input {
    flex: 1;
    border: none;
    padding: 1rem 1.25rem;
    outline: none;
    background: transparent;
    font-size: 1rem;
    color: var(--text-dark);
}

/* Submit Button */
.submit-button {
    width: 100%;
    padding: 1.25rem 2.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.submit-button:hover {
    background: linear-gradient(135deg, var(--primary-hover), var(--primary-color));
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.submit-button:active {
    transform: translateY(0);
}

/* Info Text */
.info-text {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-top: 0.75rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    line-height: 1.5;
}

.info-icon {
    color: var(--primary-color);
    margin-top: 0.125rem;
    flex-shrink: 0;
}

/* Error Messages */
.error-message {
    color: #dc2626;
    font-size: 0.9rem;
    margin-top: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

/* Login Link */
.login-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border);
}

.login-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.login-link a:hover {
    text-decoration: underline;
}

/* Desktop Layout (1024px and up) */
@media (min-width: 1024px) {
    .registration-container {
        min-height: 100vh;
    }
    
    .hero-section {
        position: sticky;
        top: 0;
        height: 100vh;
    }
    
    .form-section-container {
        max-height: 100vh;
        overflow-y: auto;
    }
}

/* Mobile Optimizations */
@media (max-width: 1023px) {
    .registration-container {
        flex-direction: column;
    }
    
    .hero-section {
        flex: none;
        min-height: 40vh;
        padding: 2rem 1.5rem;
        position: relative;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.125rem;
    }
    
    .hero-features {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: center;
    }
    
    .hero-feature {
        flex: 1;
        min-width: 250px;
        max-width: 300px;
    }
    
    .form-section-container {
        flex: none;
        max-height: none;
        overflow-y: visible;
    }
    
    .form-container {
        min-height: auto;
        padding: 2rem 1.5rem;
        max-width: 600px;
    }
    
    .form-section {
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
    }
    
    .section-icon {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1.25rem;
        margin-right: 1.25rem;
    }
    
    .section-title h2 {
        font-size: 1.375rem;
    }
}

@media (max-width: 767px) {
    .registration-container {
        flex-direction: column;
        min-height: auto;
    }
    
    .hero-section {
        min-height: 40vh;
        padding: 2rem 1.5rem;
    }
    
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-features {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .hero-feature {
        min-width: auto;
        max-width: none;
    }
    
    .form-section-container {
        max-height: none;
        overflow-y: visible;
    }
    
    .form-container {
        padding: 2rem 1.5rem;
        max-width: 100%;
        min-height: auto;
    }
    
    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
    
    .form-header h1 {
        font-size: 1.875rem;
    }
    
    .form-sections-container {
        margin-bottom: 1.5rem;
    }
    
    .form-section {
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .submit-section {
        padding-top: 2rem;
        margin-top: 1.5rem;
    }
}

/* Tablet and medium screens balance */
@media (min-width: 768px) and (max-width: 1024px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .form-container {
        padding: 2.5rem 2rem;
        max-width: 650px;
    }
    
    .form-section {
        padding: 2rem;
    }
}

/* Large screens optimization */
@media (min-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }
    
    .form-container {
        max-width: 800px;
        padding: 4rem 3rem;
    }
    
    .form-section {
        padding: 3rem;
    }
    
    .submit-section {
        padding-top: 3.5rem;
    }
}
    
    .form-header h1 {
        font-size: 2rem;
    }
    
    .form-header p {
        font-size: 1rem;
    }
    
    .form-section {
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }
    
    .section-title {
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
    }
    
    .section-icon {
        width: 2.25rem;
        height: 2.25rem;
        font-size: 1.125rem;
        margin-right: 1rem;
    }
    
    .section-title h2 {
        font-size: 1.25rem;
    }
    
    .input-group {
        margin-bottom: 1.5rem;
    }
    
    .modern-input, .phone-input {
        padding: 0.875rem 1rem;
    }
    
    .phone-prefix {
        padding: 0.875rem 1rem;
    }
    
    .form-grid {
        gap: 1.5rem;
    }
}

/* Additional styles for terms modal */
    transition: all 0.3s ease;
}

@keyframes gradientMove {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.submit-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(237, 61, 38, 0.4);
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.submit-button:hover::before {
    left: 100%;
}

/* Additional styles for terms modal */
#termsContent::-webkit-scrollbar {
    width: 8px;
}

#termsContent::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#termsContent::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

#termsContent::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.modal-backdrop {
    backdrop-filter: blur(4px);
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s ease-in-out;
}

/* Smooth progress bar animation */
#progressBar {
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Button state transitions */
#acceptTermsBtn {
    transition: all 0.3s ease;
}

/* Success message animation */
.slide-in-right {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.slide-out-right {
    animation: slideOutRight 0.5s ease-in;
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

<!-- Terms and Conditions Modal -->
<div id="termsModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity modal-backdrop" aria-hidden="true"></div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                            Syarat dan Ketentuan BIK NIGHT RUN 2025
                        </h3>
                        
                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress Membaca</span>
                                <span id="readProgress">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-orange-600 mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Harap baca hingga akhir untuk melanjutkan registrasi
                            </p>
                        </div>
                        
                        <!-- Scrollable Content -->
                        <div id="termsContent" class="mt-4 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="space-y-4 text-sm text-gray-700">
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="font-bold text-blue-900 mb-2">SYARAT & KETENTUAN</h4>
                                            <p class="text-sm text-blue-700">
                                                Dengan mengambil bagian dalam BIK NIGHT RUN 2025 (selanjutnya disebut "Acara") termasuk tetapi tidak terbatas pada mendaftar melalui website lomba, seorang pendaftar (selanjutnya disebut "PESERTA") menerima dan menyetujui untuk mematuhi dan terikat atas segala aturan dan peraturan, syarat dan ketentuan Acara yang diterapkan oleh Penyelenggara dan BIK NIGHT RUN (selanjutnya disebut sebagai "BIK NIGHT RUN").
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>1)</strong> PESERTA memastikan dan menyatakan kebenaran segala informasi yang diberikan pada saat melakukan pendaftaran. Penyelenggara dan BIK NIGHT RUN memiliki hak untuk mewajibkan PESERTA menunjukkan dokumen pengenal resmi (seperti KTP, paspor atau SIM) agar dapat berpartisipasi di Acara ini, jika dipandang perlu. Jika menurut Penyelenggara dan BIK NIGHT RUN ditemukan ketidakcocokan, PESERTA dapat didiskualifikasi dari Acara.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>2)</strong> PESERTA bertanggung jawab penuh atas dan menanggung segala risiko berupa cedera badan, kematian, atau kerusakan properti yang mungkin diderita atau didapat PESERTA karena berpartisipasi di Acara ini dan dimana cedera, kematian atau kerusakan properti mungkin muncul, sebagai akibat, atau sebagai bagian dari atau disebabkan oleh atau karena kelalaian dan/ atau pengabaian ketentuan-ketentuan (seperti yang ditunjukkan di bawah) atau salah satu dari mereka atau hal lainnya, dan terlepas hal yang sama terjadi sebelum, sesudah atau ketika berkompetisi dan/atau berpartisipasi di Acara. PESERTA mengerti dan memahami bahwa terdapat risiko-risiko dan bahaya-bahaya yang berkaitan dengan keikutsertaan di Acara ini yang dapat menyebabkan cedera badan, cacat, dan kematian. Segala risiko dan bahaya yang berkaitan dengan partisipasi di Acara ini dipahami oleh PESERTA terlepas segala risiko dan bahaya mungkin disebabkan oleh pengabaian ketentuan-ketentuan dan lainnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>3)</strong> Sehubungan dengan risiko-risiko yang ditanggung oleh PESERTA, maka PESERTA melepaskan, mengabaikan, dan setuju untuk tidak menuntut Penyelenggara/promotor, BIK NIGHT RUN, para peserta, Sponsor Acara and agen PR Acara, rekanan yang berpartisipasi, organisasi yang menjamin, (atau afiliasi lainnya), pejabat resmi, pemilik kendaraan, pengemudi, para sponsor, pemasang iklan, para pemilik, para penyewa, pemberi sewa dari lokasi lomba,yang menyelenggarakan Acara dan para petugas, para agen, dan para karyawan (untuk keperluan yang disebutkan ini akan disebut sebagai Pers) dari segala kewajiban kepada diri anda, perwakilan pribadi anda, pihak yang ditunjuk, dan para pelaksanan, dari segala dan seluruh klaim, tuntutan, kerugian atau kerusakan dari PESERTA atau kerusakan properti, terlepas hal tersebut terjadi atau disebabkan atau diduga sebagai akibat baik keseluruhan maupun sebagian karena kelalaian Pers atau lainnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>4)</strong> Dengan ini PESERTA setuju bahwa Pelepasan dan Pengabaian Tanggung Jawab, Asumsi Risiko dan Perjanjian Kerusakan sebagaimana dimaksud dalam poin 3 diatas, termasukan kelalaian operasi penyelamatan (jika ada) dan dimaksudkan seluas mungkin dan mencakup sebanyak mungkin sebagaimana yang diijinkan oleh hukum Indonesia dimana Acara ini dilaksanakan.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>5)</strong> PESERTA memahami bahwa uang pendaftaran yang dibayarkan PESERTA kepada Penyelenggara Acara, Penyelenggara Acara akan mengeluarkan biaya yang besar dan pengeluaran untuk penyelenggaraan Acara. Dengan ini PESERTA menyetujui bahwa jika terjadi kondisi dimana lomba dibatalkan disebabkan oleh kondisi yang di luar kuasa penyelanggara Acara dan BIK NIGHT RUN termasuk tapi tidak terbatas pada badai, hujan, pasang laut atau cuaca, angin, atau kuasa Tuhan atau tindakan terorisme atau kondisi-kondisi lainnya, uang pendaftaran PESERTA tidak dapat dikembalikan.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>6)</strong> Setelah pendaftaran selesai dilaksanakan, PESERTA yang akhirnya tidak dapat berpartisipasi di Acara tidak akan mendapatkan pengembalian uang pendaftaran. Slot lomba tidak dapat dipindahkan kepada orang lain atau PESERTA tidak boleh mengubah kategori lomba menjadi kategori jarak yang lebih tinggi atau kategori jarak yang lebih rendah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>7)</strong> Salinan surat elektronik konfirmasi bersama dengan satu (1) buah tanda pengenal resmi harus ditunjukkan oleh PESERTA ketika mengambil paket lomba. Surat kuasa yang disertai dengan salinan tanda pengenal dibutuhkan jika PESERTA mengambil paket lomba atas nama PESERTA yang lain.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>8)</strong> Paket lomba harus diambil sesuai dengan hari pengambilan yang sudah dijadwalkan. Permintaan untuk mengambil setelah hari yang sudah ditetapkan tidak akan dilayani.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>9)</strong> PESERTA yang berusia di bawah 15 tahun harus didampingi oleh orang tua atau wali ketika mengambil paket lomba. Orang tua atau wali akan diminta untuk menandatangani surat persetujuan dan menunjukkan tanda pengenal resmi berupa KTP/Paspor/SIM.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>10)</strong> PESERTA yang tidak memulai lomba sesuai kategorinya tidak akan diperbolehkan berpartisipasi dan secara otomatis akan didiskualifikasi dari lomba. Sama halnya, PESERTA yang memulai lomba lebih awal dari kategori yang dia ikuti juga akan didiskualifikasi.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>11)</strong> PESERTA diwajibkan untuk menggunakan nomor dada lomba yang telah disediakan. PESERTA yang tidak menggunakan nomor dada akan diminta untuk keluar dari rute oleh petugas keamanan dan/ atau Penyelenggara.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>12)</strong> Penutupan jalan akan berakhir setelah dua (2) jam sejak lomba dimulai. PESERTA yang meneruskan lomba diperbolehkan untuk melanjutkan lomba tetapi dengan menanggung sendiri atas risiko yang mungkin muncul: (a) Penyelenggara dan/atau BIK NIGHT RUN memiliki hak untuk sewaktu-waktu mengakhiri penutupan jalan lebih awal dari yang dijadwalkan atas kebijakan Penyelenggara dan/atau BIK NIGHT RUN; dan (b) Mengakhiri penutupan jalan berdasarkan persetujuan dari pihak pemerintah yang berwenang jika penutupan jalan berakhir lebih awal.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>13)</strong> Binatang peliharaan, sepeda, sepatu roda, kereta bayi, kereta dorong, sepatu yang beroda atau dapat dipasang roda dan objek lain yang memiliki roda tidak diperbolehkan untuk berada di rute selain kendaraan lomba resmi dan kendaraan medis.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>14)</strong> Rute lomba dapat diubah sewaktu-waktu untuk alasan keamanan peserta dan pertimbangan lalu lintas.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>15)</strong> Semua pemenang wajib hadir pada saat Acara Penyerahan Hadiah untuk menerima penghargaan dan hadiah. Penyelenggara memiliki hak untuk tidak memberikan hadiah (baik dalam bentuk uang tunai maupun bentuk lain) untuk pemenang-pemenang yang tidak hadir pada saat Acara Penyerahan Hadiah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>16)</strong> Keputusan Penyelenggara bersifat final. Korespondensi atau perselisihan lebih lanjut tidak akan dilayani.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>17)</strong> Para pemenang podium diminta untuk menunjukkan tanda pengenal yang sah dan diakui Penyelenggara dan BIK NIGHT RUN untuk mengklaim penghargaan dan hadiah.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>18)</strong> Untuk para pemenang podium atau para pemenang podium potensial, perselisihan dan protes harus disampaikan di tempat dalam waktu tiga puluh (30) menit setelah hasil dipublikasikan di papan pengumuman hasil lomba atau tepat setelah upAcara pemenang, yang mana yang lebih dulu.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>19)</strong> Segala hadiah diberikan berdasarkan persediaan dimana Penyelenggara dan BIK NIGHT RUN memiliki hak untuk membatalkan, mengubah, mengganti atau membatalkan segala hadiah sewaktu-waktu dengan atau tanpa melakukan pemberitahuan kepada Peserta.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>20)</strong> Segala hadiah dan premium tidak dapat dipertukarkan dan tidak dapat diuangkan atas segala kondisi apapun.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>21)</strong> Penyelenggara memilki hak untuk mewajibkan PESERTA menunjukkan dokumen pengenal resmi (seperti KTP, Paspor, KITAS atau SIM) yang dibutuhkan untuk mendukung terjadinya perselisihan atau pengajuan protes.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>22)</strong> Dengan berpartisipasi di Acara ini, PESERTA setuju untuk ambil bagian dalam segala promosi atau publikasi yang akan dilakukan oleh BIK NIGHT RUN dan dengan ini PESERTA dan tanpa syarat memberikan hak kepada BIK NIGHT RUN untuk merekam dan menggunakan performa peserta, penampilan, kesukaan, nama, suara dan/atau hal tertentu dari PESERTA (jika dimungkinkan) di segala cara yang BIK NIGHT RUN anggap pantas. PESERTA mengetahui bahwa BIK NIGHT RUN akan memiliki kebebasan untuk mempublikasi dan menggunakan segala bentuk rekaman yang dibuat oleh BIK NIGHT RUN, termasuk tapi tidak terbatas pada rekaman telefon, rekaman suara, rekaman visual dan foto (jika ada), untuk keperluan promosi dan publikasi Acara (sekarang atau di masa yang akan datang). Jika dimungkinkan, setiap PESERTA mengabaikan segala bentuk hak cipta intelektual yang PESERTA mungkin dapatkan atau punya di bawah hukum (dan segala aturan lanjutan atau amendemen lebih lanjut) berkaitan dengan rekaman-rekaman yang sudah disebutkan tadi dan bentuk lainnya atau hak moral yang peserta mungkin punya atau dapatkan atas nama hukum.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>23)</strong> Penyelenggara dan/ atau BIK NIGHT RUN memiliki hak untuk membatasi dan/atau menolak pendaftaran PESERTA untuk mengikuti Acara tanpa perlu memberitahukan alasan apa pun.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>24)</strong> Penyelenggara dan/ atau BIK NIGHT RUN memiliki hak untuk mengubah segala syarat-syarat dan ketentuan-ketentuan dan/atau menghentikan Acara berdasarkan kebijakan Penyelenggara dan/ atau BIK NIGHT RUN dan itu dilakukan tanpa pemberitahuan sebelumnya.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>25)</strong> Bahwa dengan ini Peserta mengetahui dan menyetujui apabila terdapat Peserta yang tidak kunjung mengambil BIB pada waktu yang ditentukan (pada saat Racepack Collection yaitu tanggal 05-06 September 2025) kemudian tidak ada konfirmasi kepada pihak penyelenggara, maka BIB dinyatakan hangus/tidak dapat digunakan oleh Peserta.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>26)</strong> Sehubungan dengan hal tersebut kami selaku Pihak BIK NIGHT RUN 2025 berharap bahwa Peserta dapat mengambil Race Pack pada tgl 05-06 September 2025.
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="mb-3 text-justify">
                                        <strong>27)</strong> Dengan adanya hal tersebut maka Peserta membebaskan Pihak Penyelenggara BIK NIGHT RUN 2025 dari segala jenis tanggung jawab dan/atau ganti rugi dalam bentuk apapun.
                                    </p>
                                </div>
                                
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">
                                                <strong>PENTING:</strong> Dengan mendaftar dalam BIK NIGHT RUN 2025, peserta dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku di atas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-6 pt-4 border-t border-gray-300">
                                    <p class="text-sm text-gray-600 font-medium">
                                        BIK NIGHT RUN 2025
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Untuk informasi lebih lanjut, hubungi panitia melalui kontak yang tersedia.
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Dokumen ini telah Anda baca hingga akhir. Terima kasih.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="acceptTermsBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm cursor-not-allowed" disabled>
                    <i class="fas fa-spinner fa-spin mr-2" id="loadingIcon"></i>
                    <span id="btnText">Membaca... (0%)</span>
                </button>
                <button type="button" id="closeTermsBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<div class="registration-container">
    <!-- Hero Section (Left Side) -->
    <div class="hero-section">
        <div class="hero-content">
            <div class="hero-title">
                BIK SULTRA RUN 2025
            </div>
            <div class="hero-subtitle">
                Dalam rangka Bulan inklusi keuangan nasional 2025
            </div>
            
            <div class="hero-features">
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Multiple Categories</div>
                        <div class="text-sm opacity-90">Fun Run, 5K, 10K, Half Marathon</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Official Certificate</div>
                        <div class="text-sm opacity-90">Digital certificate for all finishers</div>
                    </div>
                </div>
                
                <div class="hero-feature">
                    <div class="hero-feature-icon">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <div>
                        <div class="font-semibold">Race Pack</div>
                        <div class="text-sm opacity-90">Jersey, bib number, and goodies</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Section (Right Side) -->
    <div class="form-section-container">
        <div class="form-container">
            <!-- TOP SECTION - Header -->
            <div class="form-header">
                <h1>Registrasi Night Run</h1>
                <p>Bergabunglah dengan petualangan lari malam yang tak terlupakan</p>
            </div>
            
            <!-- MIDDLE SECTION - Form Content -->
            <form id="registrationForm" method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-sections-container">
                
                <!-- Personal Information Section -->
                <div class="form-section" style="animation-delay: 0.1s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <h2>Informasi Pribadi</h2>
                    </div>
                    
                    <div class="form-grid form-grid-2">
                        <div class="input-group">
                            <label for="name" class="input-label">
                                Nama Lengkap <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Masukkan nama lengkap sesuai KTP" required>
                            @error('name')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label for="no_ktp" class="input-label">
                                No KTP <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input @error('no_ktp') border-red-500 @enderror" 
                                   id="no_ktp" name="no_ktp" value="{{ old('no_ktp') }}" 
                                   placeholder="Masukkan nomor KTP (16 digit)"
                                   pattern="[0-9]{16}"
                                   maxlength="16"
                                   title="Nomor KTP harus 16 digit angka"
                                   required>
                            @error('no_ktp')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="info-text">
                                <i class="fas fa-info-circle info-icon"></i>
                                Masukkan 16 digit nomor KTP tanpa tanda baca
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="gender" class="input-label">
                            Jenis Kelamin <span class="required-mark">*</span>
                        </label>
                        <select class="modern-input modern-select @error('gender') border-red-500 @enderror" 
                                id="gender" name="gender" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-grid form-grid-2">
                        <div class="input-group">
                            <label for="birth_place" class="input-label">
                                Tempat Lahir <span class="required-mark">*</span>
                            </label>
                            <input type="text" 
                                   class="modern-input @error('birth_place') border-red-500 @enderror" 
                                   id="birth_place" name="birth_place" value="{{ old('birth_place') }}" 
                                   placeholder="Contoh: Jakarta" required>
                            @error('birth_place')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label for="birth_date" class="input-label">
                                Tanggal Lahir <span class="required-mark">*</span>
                            </label>
                            <input type="date" 
                                   class="modern-input @error('birth_date') border-red-500 @enderror" 
                                   id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                                   max="{{ date('Y-m-d', strtotime('-10 years')) }}" required>
                            @error('birth_date')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="info-text">
                                <i class="fas fa-info-circle info-icon"></i>
                                Minimal umur 10 tahun
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="regency_search" class="input-label">
                            Kota/Kabupaten Tempat Tinggal <span class="required-mark">*</span>
                        </label>
                        <div class="location-autocomplete-container">
                            <input type="text" 
                                   class="modern-input @error('regency_name') border-red-500 @enderror" 
                                   id="regency_search" 
                                   name="regency_search"
                                   value="{{ old('regency_name') }}" 
                                   placeholder="Ketik nama kota/kabupaten tempat tinggal..."
                                   data-location-autocomplete
                                   data-hidden-input="#regency_id"
                                   autocomplete="off"
                                   required>
                            <input type="hidden" id="regency_id" name="regency_id" value="{{ old('regency_id') }}">
                            <input type="hidden" id="regency_name" name="regency_name" value="{{ old('regency_name') }}">
                            <input type="hidden" id="province_name" name="province_name" value="{{ old('province_name') }}">
                        </div>
                        @error('regency_name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        @error('regency_id')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="info-text">
                            <i class="fas fa-info-circle info-icon"></i>
                            Ketik minimal 2 karakter untuk mencari
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="address" class="input-label">
                            Alamat Lengkap <span class="required-mark">*</span>
                        </label>
                        <textarea class="modern-input modern-textarea @error('address') border-red-500 @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Masukkan alamat lengkap tempat tinggal" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Race Information Section -->
                <div class="form-section" style="animation-delay: 0.2s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h2>Informasi Lomba</h2>
                    </div>
                    
                    <div class="form-grid form-grid-2">
                        <div class="input-group">
                            <label for="race_category" class="input-label">
                                Kategori Lomba <span class="required-mark">*</span>
                            </label>
                            <select class="modern-input modern-select @error('race_category') border-red-500 @enderror" 
                                    id="race_category" name="race_category" required>
                                <option value="">Pilih kategori lomba</option>
                                <option value="5K" {{ old('race_category') == '5K' ? 'selected' : '' }}>5K</option>
                                <!-- <option value="10K" {{ old('race_category') == '10K' ? 'selected' : '' }}>10K</option>
                                <option value="21K" {{ old('race_category') == '21K' ? 'selected' : '' }}>21K - Half Marathon</option> -->
                            </select>
                            @error('race_category')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label for="jersey_size" class="input-label">
                                Ukuran Jersey <span class="required-mark">*</span>
                            </label>
                            <select class="modern-input modern-select @error('jersey_size') border-red-500 @enderror" 
                                    id="jersey_size" name="jersey_size" required>
                                <option value="">Pilih ukuran jersey</option>
                                @foreach($jerseySizes as $size)
                                    <option value="{{ $size->name }}" {{ old('jersey_size') == $size->name ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jersey_size')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="bib_name" class="input-label">
                            Nama BIB <span class="required-mark">*</span>
                        </label>
                        <input type="text" 
                               class="modern-input @error('bib_name') border-red-500 @enderror" 
                               id="bib_name" name="bib_name" value="{{ old('bib_name') }}" 
                               placeholder="Nama yang akan tercetak di BIB" maxlength="20" required>
                        @error('bib_name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="info-text">
                            <i class="fas fa-info-circle info-icon"></i>
                            Maksimal 20 karakter untuk nama di BIB
                        </div>
                    </div>
                </div>

                <!-- Ticket Type Information -->
                <div class="form-section hidden" id="ticketInfo" style="animation-delay: 0.3s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h2>Informasi Tiket</h2>
                    </div>
                    
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <div class="form-grid form-grid-2">
                            <div>
                                <div class="ticket-type-info">
                                    <h3 class="text-lg font-semibold text-gray-800 ticket-type-name">-</h3>
                                    <p class="text-2xl font-bold text-green-600 ticket-price">Rp 0</p>
                                    <div class="ticket-quota hidden">
                                        <small class="text-gray-600">Kuota tersisa: <span class="remaining-quota font-semibold">-</span></small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="countdown-timer">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Sisa Waktu:</h3>
                                    <div class="timer-display flex space-x-1">
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-days">0</span>
                                            <span class="text-xs text-gray-600">hari</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-hours">0</span>
                                            <span class="text-xs text-gray-600">jam</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-minutes">0</span>
                                            <span class="text-xs text-gray-600">menit</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="bg-red-500 text-white px-2 py-1 rounded timer-seconds">0</span>
                                            <span class="text-xs text-gray-600">detik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section" style="animation-delay: 0.4s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h2>Informasi Kontak</h2>
                    </div>
                    
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="email" class="input-label">
                                Email <span class="required-mark">*</span>
                            </label>
                            <input type="email" 
                                   class="modern-input @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="contoh@email.com" required>
                            @error('email')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label for="whatsapp_number" class="input-label">
                                No Kontak WhatsApp <span class="required-mark">*</span>
                                <small style="color: var(--text-light);">(akan divalidasi otomatis)</small>
                            </label>
                            <div class="phone-input-group">
                                <span class="phone-prefix">+62</span>
                                <input type="text" 
                                       class="phone-input @error('whatsapp_number') border-red-500 @enderror" 
                                       id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" 
                                       placeholder="8114000805" required>
                            </div>
                            @error('whatsapp_number')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="info-text">
                                <i class="fas fa-info-circle info-icon"></i>
                                Masukkan nomor tanpa awalan 0 atau +62. Contoh: 8114000805 (awalan akan dihapus otomatis)
                            </div>
                            <!-- WhatsApp validation status -->
                            <div id="whatsapp-validation-status" class="mt-2"></div>
                        </div>

                        <div class="input-group">
                            <label for="phone" class="input-label">
                                Nomor HP Alternatif
                            </label>
                            <input type="text" 
                                   class="modern-input @error('phone') border-red-500 @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('phone')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-grid form-grid-2">
                            <div class="input-group">
                                <label for="emergency_contact_name" class="input-label">
                                    Nama Kontak Darurat <span class="required-mark">*</span>
                                </label>
                                <input type="text" 
                                       class="modern-input @error('emergency_contact_name') border-red-500 @enderror" 
                                       id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" 
                                       placeholder="Nama kontak darurat" required>
                                @error('emergency_contact_name')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="emergency_contact_phone" class="input-label">
                                    Nomor Kontak Darurat <span class="required-mark">*</span>
                                </label>
                                <input type="tel" 
                                       class="modern-input @error('emergency_contact_phone') border-red-500 @enderror" 
                                       id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" 
                                       placeholder="08xxxxxxxxxx" 
                                       pattern="[0-9+\-\s]+"
                                       title="Hanya angka, tanda +, -, dan spasi yang diperbolehkan"
                                       required>
                                @error('emergency_contact_phone')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="info-text">
                                    <i class="fas fa-info-circle info-icon"></i>
                                    Format: 08xxxxxxxxxx atau +628xxxxxxxxxx
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section" style="animation-delay: 0.5s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2>Informasi Tambahan</h2>
                    </div>
                    
                    <div class="form-grid">
                        <div class="input-group">
                            <label for="group_community" class="input-label">
                                Group Lari/Komunitas/Instansi
                            </label>
                            <input type="text" 
                                   class="modern-input @error('group_community') border-red-500 @enderror" 
                                   id="group_community" name="group_community" value="{{ old('group_community') }}" 
                                   placeholder="Nama komunitas/instansi (opsional)">
                            @error('group_community')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-grid form-grid-2">
                            <div class="input-group">
                                <label for="blood_type" class="input-label">
                                    Golongan Darah <span class="required-mark">*</span>
                                </label>
                                <select class="modern-input modern-select @error('blood_type') border-red-500 @enderror" 
                                        id="blood_type" name="blood_type" required>
                                    <option value="">Pilih Golongan Darah</option>
                                    @foreach($bloodTypes as $type)
                                        <option value="{{ $type->name }}" {{ old('blood_type') == $type->name ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="input-group">
                                <label for="occupation" class="input-label">
                                    Pekerjaan <span class="required-mark">*</span>
                                </label>
                                <input type="text" 
                                       class="modern-input @error('occupation') border-red-500 @enderror" 
                                       id="occupation" name="occupation" value="{{ old('occupation') }}" 
                                       placeholder="Masukkan pekerjaan Anda" required>
                                @error('occupation')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="medical_history" class="input-label">
                                Riwayat Penyakit
                            </label>
                            <textarea class="modern-input modern-textarea @error('medical_history') border-red-500 @enderror" 
                                      id="medical_history" name="medical_history" rows="3" 
                                      placeholder="Sebutkan riwayat penyakit yang relevan (opsional)">{{ old('medical_history') }}</textarea>
                            @error('medical_history')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="input-group">
                            <label for="event_source" class="input-label">
                                Tau Event Ini Darimana? <span class="required-mark">*</span>
                            </label>
                            <select class="modern-input modern-select @error('event_source') border-red-500 @enderror" 
                                    id="event_source" name="event_source" required>
                                <option value="">Pilih Sumber Informasi</option>
                                @foreach($eventSources as $source)
                                    <option value="{{ $source->name }}" {{ old('event_source') == $source->name ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_source')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="form-section" style="animation-delay: 0.6s;">
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <h2>Informasi Akun</h2>
                    </div>
                    
                    <!-- Password Auto-Generation Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-magic text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-2">Password Otomatis</h4>
                                <p class="text-blue-700 text-sm">
                                    Sistem akan membuat password yang aman dan mengirimkannya ke WhatsApp Anda setelah registrasi.
                                </p>
                                <p class="text-blue-600 text-xs mt-1">
                                    Format password: 2 huruf + 4 angka (contoh: ab1234)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields for auto password -->
                    <input type="hidden" name="use_random_password" value="1">
                    <input type="hidden" name="password_type" value="simple">
                </div>
                </div>

                <!-- BOTTOM SECTION - Submit & Account Info -->
                <div class="submit-section">
                    <div class="text-center">
                        <button type="submit" class="submit-button">
                            <i class="fas fa-running mr-2"></i>
                            Daftar Event Lari
                        </button>
                        
                        <div class="info-text mt-6 justify-center text-center">
                            <i class="fas fa-info-circle info-icon"></i>
                            <span>Setelah registrasi, password akan dikirim ke WhatsApp Anda untuk login</span>
                        </div>
                        
                        <div class="login-link">
                            <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Terms Modal Management
    const termsModal = document.getElementById('termsModal');
    const termsContent = document.getElementById('termsContent');
    const progressBar = document.getElementById('progressBar');
    const readProgress = document.getElementById('readProgress');
    const acceptTermsBtn = document.getElementById('acceptTermsBtn');
    const closeTermsBtn = document.getElementById('closeTermsBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnText = document.getElementById('btnText');
    const formContainer = document.querySelector('.form-container');
    
    let isTermsAccepted = false;
    let readPercentage = 0;
    let hasReachedEnd = false;
    let autoCloseTimer = null;
    
    // Show terms modal on page load - DISABLED FOR DEBUGGING
    // showTermsModal();
    
    // Show form immediately - DEBUGGING MODE
    if (formContainer) {
        formContainer.style.display = 'block';
        isTermsAccepted = true;
    }
    
    function showTermsModal() {
        termsModal.classList.remove('hidden');
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function hideTermsModal() {
        termsModal.classList.add('hidden');
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Show form after terms are accepted
        if (formContainer && isTermsAccepted) {
            formContainer.style.display = 'block';
        }
    }
    
    // Track scrolling in terms content
    termsContent.addEventListener('scroll', function() {
        const scrollTop = this.scrollTop;
        const scrollHeight = this.scrollHeight;
        const clientHeight = this.clientHeight;
        const scrollable = scrollHeight - clientHeight;
        
        if (scrollable > 0) {
            readPercentage = Math.round((scrollTop / scrollable) * 100);
            readPercentage = Math.min(100, Math.max(0, readPercentage));
        } else {
            readPercentage = 100; // If content is not scrollable, consider as fully read
        }
        
        // Update progress bar
        progressBar.style.width = readPercentage + '%';
        readProgress.textContent = readPercentage + '%';
        
        // Update button text
        if (readPercentage < 100) {
            btnText.textContent = `Membaca... (${readPercentage}%)`;
            acceptTermsBtn.disabled = true;
            acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm cursor-not-allowed';
            loadingIcon.style.display = 'inline-block';
        } else {
            // User has read to the end
            if (!hasReachedEnd) {
                hasReachedEnd = true;
                btnText.textContent = 'Setuju & Lanjutkan';
                acceptTermsBtn.disabled = false;
                acceptTermsBtn.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm';
                loadingIcon.style.display = 'none';
                
                // Show success message
                showReadCompleteMessage();
                
                // Start auto-close countdown
                startAutoCloseCountdown();
            }
        }
    });
    
    function showReadCompleteMessage() {
        // Create a temporary success message
        const successMsg = document.createElement('div');
        successMsg.className = 'bg-green-50 border border-green-200 rounded-lg p-3 mb-4';
        successMsg.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                <span class="text-green-800 text-sm font-medium">
                    Anda telah membaca seluruh syarat dan ketentuan!
                </span>
            </div>
        `;
        
        // Insert before the scrollable content
        termsContent.parentNode.insertBefore(successMsg, termsContent);
        
        // Remove after 5 seconds
        setTimeout(() => {
            if (successMsg.parentNode) {
                successMsg.parentNode.removeChild(successMsg);
            }
        }, 5000);
    }
    
    function startAutoCloseCountdown() {
        let countdown = 10; // Auto close in 10 seconds
        
        function updateCountdown() {
            if (countdown > 0 && hasReachedEnd && !isTermsAccepted) {
                btnText.textContent = `Setuju & Lanjutkan (${countdown}s)`;
                countdown--;
                autoCloseTimer = setTimeout(updateCountdown, 1000);
            } else if (countdown === 0 && hasReachedEnd && !isTermsAccepted) {
                // Auto accept terms
                acceptTerms();
            }
        }
        
        updateCountdown();
    }
    
    function acceptTerms() {
        isTermsAccepted = true;
        
        // Clear auto-close timer
        if (autoCloseTimer) {
            clearTimeout(autoCloseTimer);
            autoCloseTimer = null;
        }
        
        // Store acceptance in localStorage
        localStorage.setItem('termsAccepted', 'true');
        localStorage.setItem('termsAcceptedTime', new Date().toISOString());
        
        // Hide modal and show form
        hideTermsModal();
        
        // Show success notification
        showSuccessNotification();
    }
    
    function showSuccessNotification() {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 translate-x-full';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Syarat dan ketentuan telah diterima</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }
        }, 5000);
    }
    
    // Accept terms button click
    acceptTermsBtn.addEventListener('click', function() {
        if (hasReachedEnd) {
            acceptTerms();
        }
    });
    
    // Close terms button (cancel registration)
    closeTermsBtn.addEventListener('click', function() {
        if (confirm('Anda yakin ingin membatalkan registrasi? Anda perlu menyetujui syarat dan ketentuan untuk melanjutkan.')) {
            window.location.href = '/'; // Redirect to home page
        }
    });
    
    // Prevent closing modal by clicking backdrop or ESC
    termsModal.addEventListener('click', function(e) {
        if (e.target === this) {
            // Shake modal to indicate it can't be closed
            const modalContent = this.querySelector('.relative');
            modalContent.classList.add('animate-pulse');
            setTimeout(() => {
                modalContent.classList.remove('animate-pulse');
            }, 1000);
        }
    });
    
    // Disable ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !isTermsAccepted) {
            e.preventDefault();
            // Shake modal
            const modalContent = termsModal.querySelector('.relative');
            modalContent.classList.add('animate-pulse');
            setTimeout(() => {
                modalContent.classList.remove('animate-pulse');
            }, 1000);
        }
    });
    
    // Check if terms were previously accepted (optional - for development)
    // Comment out these lines for production to always show terms
    /*
    const previouslyAccepted = localStorage.getItem('termsAccepted');
    const acceptedTime = localStorage.getItem('termsAcceptedTime');
    
    if (previouslyAccepted === 'true' && acceptedTime) {
        const acceptedDate = new Date(acceptedTime);
        const now = new Date();
        const hoursDiff = (now - acceptedDate) / (1000 * 60 * 60);
        
        // Terms acceptance valid for 24 hours
        if (hoursDiff < 24) {
            isTermsAccepted = true;
            hideTermsModal();
        }
    }
    */

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="text"][placeholder*="081"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters
            let value = this.value.replace(/\D/g, '');
            
            // Limit to 15 digits
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            
            // Add formatting if starts with 0
            if (value.startsWith('0')) {
                this.value = value;
            } else if (value.startsWith('62')) {
                this.value = value;
            } else if (value.length > 0) {
                this.value = '0' + value;
            }
        });
    });

    // Form validation on submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let hasError = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                field.classList.remove('border-gray-300');
                hasError = true;
            } else {
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-300');
            }
        });

        // Birth date validation
        const birthDate = document.getElementById('birth_date');
        if (birthDate.value) {
            const today = new Date();
            const birth = new Date(birthDate.value);
            const age = Math.floor((today - birth) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age < 10) {
                birthDate.classList.add('border-red-500');
                birthDate.classList.remove('border-gray-300');
                hasError = true;
                alert('Minimal umur 10 tahun untuk mengikuti event ini.');
            }
        }

        if (hasError) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });

    // WhatsApp number validation - SIMPLIFIED FOR DEBUGGING
    const whatsappInput = document.getElementById('whatsapp_number');
    const whatsappStatus = document.getElementById('whatsapp-validation-status');
    const submitBtn = form.querySelector('button[type="submit"]');

    let validationTimeout;
    let lastValidatedNumber = '';
    let isValidWhatsApp = true; // DEBUGGING: Always consider valid

    // Format WhatsApp number input - BASIC FORMATTING ONLY
    whatsappInput.addEventListener('input', function() {
        let phoneNumber = this.value.trim();
        
        // Auto-remove leading 0 or +62
        if (phoneNumber.startsWith('0')) {
            phoneNumber = phoneNumber.substring(1);
            this.value = phoneNumber;
        } else if (phoneNumber.startsWith('+62')) {
            phoneNumber = phoneNumber.substring(3);
            this.value = phoneNumber;
        } else if (phoneNumber.startsWith('62')) {
            phoneNumber = phoneNumber.substring(2);
            this.value = phoneNumber;
        }
        
        // Only allow numeric input
        phoneNumber = phoneNumber.replace(/\D/g, '');
        this.value = phoneNumber;
        
        // Reset validation state to success
        this.classList.remove('border-red-500');
        this.classList.add('border-green-500');
        isValidWhatsApp = true;
        whatsappStatus.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded-lg"><i class="fas fa-check-circle mr-2"></i>Format nomor valid</div>';
    });

    // WhatsApp validation functions - DISABLED FOR DEBUGGING
    /*
    // Auto-validate on blur (when user leaves the field)
    whatsappInput.addEventListener('blur', function() {
        const phoneNumber = this.value.trim();
        if (phoneNumber.length >= 9 && phoneNumber !== lastValidatedNumber) {
            clearTimeout(validationTimeout);
            validateWhatsAppNumber(phoneNumber);
        }
    });

    // Handle paste event to clean up pasted content
    whatsappInput.addEventListener('paste', function(e) {
        setTimeout(() => {
            let phoneNumber = this.value.trim();
            
            // Auto-remove leading 0 or +62 from pasted content
            if (phoneNumber.startsWith('0')) {
                phoneNumber = phoneNumber.substring(1);
            } else if (phoneNumber.startsWith('+62')) {
                phoneNumber = phoneNumber.substring(3);
            } else if (phoneNumber.startsWith('62')) {
                phoneNumber = phoneNumber.substring(2);
            }
            
            // Only allow numeric input
            phoneNumber = phoneNumber.replace(/\D/g, '');
            this.value = phoneNumber;
            
            // Auto-validate if number is long enough
            if (phoneNumber.length >= 9) {
                clearTimeout(validationTimeout);
                validationTimeout = setTimeout(() => {
                    validateWhatsAppNumber(phoneNumber);
                }, 1000);
            }
        }, 10);
    });

    // Validate WhatsApp number function
    function validateWhatsAppNumber(phoneNumber) {
        if (!phoneNumber || phoneNumber.length < 9) {
            showValidationStatus('error', 'Nomor WhatsApp tidak valid');
            return;
        }

        // Show loading state
        showValidationStatus('loading', 'Memvalidasi nomor WhatsApp...');

        // Format to full international format
        const fullNumber = '62' + phoneNumber;
        
        // Make API call to validate
        fetch('{{ secure_url(route("validate-whatsapp", [], false)) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                whatsapp_number: fullNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.valid) {
                isValidWhatsApp = true;
                lastValidatedNumber = phoneNumber;
                whatsappInput.classList.add('border-green-500');
                whatsappInput.classList.remove('border-red-500', 'border-gray-300');
                showValidationStatus('success', 'Nomor WhatsApp valid dan terdaftar');
            } else if (data.success && !data.valid) {
                isValidWhatsApp = false;
                whatsappInput.classList.add('border-red-500');
                whatsappInput.classList.remove('border-green-500', 'border-gray-300');
                showValidationStatus('error', data.message || 'Nomor WhatsApp tidak valid atau tidak terdaftar');
            } else {
                // If API responds but validation fails, check if it's a service error or invalid number
                if (data.success === false) {
                    // True service error (timeout, connection failed) - allow with warning
                    isValidWhatsApp = true;
                    lastValidatedNumber = phoneNumber;
                    whatsappInput.classList.add('border-yellow-500');
                    whatsappInput.classList.remove('border-red-500', 'border-green-500', 'border-gray-300');
                    showValidationStatus('warning', 'Service WhatsApp tidak tersedia. Registrasi tetap dapat dilanjutkan.');
                } else {
                    // Other errors - block registration
                    isValidWhatsApp = false;
                    whatsappInput.classList.add('border-red-500');
                    whatsappInput.classList.remove('border-green-500', 'border-gray-300', 'border-yellow-500');
                    showValidationStatus('error', data.message || 'Nomor WhatsApp tidak valid');
                }
            }
        })
        .catch(error => {
            console.error('Validation error:', error);
            // Network/connection errors - block registration to be safe
            isValidWhatsApp = false;
            whatsappInput.classList.add('border-red-500');
            whatsappInput.classList.remove('border-green-500', 'border-gray-300', 'border-yellow-500');
            showValidationStatus('error', 'Validasi WhatsApp gagal. Silakan coba lagi.');
        })
        .finally(() => {
            updateSubmitButton();
        });
    }

    // Show validation status
    function showValidationStatus(type, message) {
        let className = '';
        let icon = '';
        
        switch(type) {
            case 'loading':
                className = 'bg-blue-50 border border-blue-200 text-blue-800';
                icon = 'fas fa-spinner fa-spin';
                break;
            case 'success':
                className = 'bg-green-50 border border-green-200 text-green-800';
                icon = 'fas fa-check-circle';
                break;
            case 'warning':
                className = 'bg-yellow-50 border border-yellow-200 text-yellow-800';
                icon = 'fas fa-exclamation-triangle';
                break;
            case 'error':
                className = 'bg-red-50 border border-red-200 text-red-800';
                icon = 'fas fa-exclamation-circle';
                break;
        }
        
        whatsappStatus.innerHTML = `
            <div class="${className} px-4 py-2 rounded-lg">
                <i class="${icon} mr-2"></i>${message}
            </div>
        `;
    }
    */    // Update submit button state - SIMPLIFIED
    function updateSubmitButton() {
        // Always allow submit for debugging
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
        }
    }

    // Handle form submission - USE NORMAL FORM SUBMISSION
    form.addEventListener('submit', function(e) {
        // ALLOW NORMAL FORM SUBMISSION - DON'T PREVENT DEFAULT
        console.log('Form submitting normally to:', form.action);
        
        // Optional: Simple validation before submit
        const requiredFields = form.querySelectorAll('[required]');
        let hasError = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Let form submit normally
        return true;
    });

    // AJAX SUBMISSION DISABLED - USING NORMAL FORM POST
    /*
    // Submit registration via API
    function submitRegistration() {
        const formData = new FormData(form);
        
        // Prepare data with proper field mapping
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('whatsapp_number'), // API expects 'phone' field, use whatsapp number
            category: formData.get('race_category'), // Backend expects 'category' not 'race_category'
            
            // Location data - send regency_search as city for auto-resolution
            city: formData.get('regency_search'), // Send search input as city for auto-resolution
            regency_id: formData.get('regency_id'),
            regency_name: formData.get('regency_name'),
            province_name: formData.get('province_name'),
            
            // Additional fields (may not be processed by basic API but keeping for completeness)
            bib_name: formData.get('bib_name'),
            gender: formData.get('gender'),
            birth_place: formData.get('birth_place'),
            birth_date: formData.get('birth_date'),
            address: formData.get('address'),
            jersey_size: formData.get('jersey_size'),
            whatsapp_number: formData.get('whatsapp_number'),
            emergency_contact_name: formData.get('emergency_contact_name'),
            emergency_contact_phone: formData.get('emergency_contact_phone'),
            group_community: formData.get('group_community'),
            blood_type: formData.get('blood_type'),
            occupation: formData.get('occupation'),
            medical_history: formData.get('medical_history'),
            event_source: formData.get('event_source')
        };

        // Debug: log the data being sent
        console.log('Sending registration data:', data);
        
        // Validate required fields before sending
        if (!data.name || !data.email || !data.phone || !data.category) {
            alert('Mohon lengkapi semua field yang wajib diisi (Nama, Email, WhatsApp, Kategori).');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            return;
        }

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Registrasi berhasil! Nomor registrasi: ' + data.data.registration_number + 
                      '\n\nSilakan login dengan email dan password yang telah dikirim ke WhatsApp Anda.');
                // Redirect to login page
                window.location.href = '/login';
            } else {
                alert('Registrasi gagal: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            console.error('Registration error:', error);
            alert('Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    }
    */

    // Load reCAPTCHA script
    const script = document.createElement('script');
    script.src = 'https://www.google.com/recaptcha/api.js?render={{ config("services.recaptcha.site_key") }}';
    document.head.appendChild(script);

    // Enhanced ticket functionality with auto-refresh and quota monitoring
    const raceCategorySelect = document.getElementById('race_category');
    const ticketInfoSection = document.getElementById('ticketInfo');
    let currentTicketData = null;
    let isLoadingTicketInfo = false;
    let countdownInterval = null;
    let quotaRefreshInterval = null;
    let currentSelectedCategory = null;
    let isQuotaAvailable = true;

    // Handle race category change
    if (raceCategorySelect) {
        raceCategorySelect.addEventListener('change', function() {
            const category = this.value;
            
            // Clear any existing intervals
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            if (quotaRefreshInterval) {
                clearInterval(quotaRefreshInterval);
                quotaRefreshInterval = null;
            }
            
            currentSelectedCategory = category;
            
            if (category) {
                fetchTicketInfo(category);
                // Start quota auto-refresh every 5 seconds
                startQuotaAutoRefresh(category);
            } else {
                hideTicketInfo();
                currentSelectedCategory = null;
            }
        });
    }

    // Start quota auto-refresh every 5 seconds
    function startQuotaAutoRefresh(category) {
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
        }
        
        quotaRefreshInterval = setInterval(() => {
            if (currentSelectedCategory === category) {
                refreshQuotaOnly(category);
            }
        }, 5000); // Update every 5 seconds
    }

    // Light API request to refresh only quota information
    function refreshQuotaOnly(category) {
        if (!category || isLoadingTicketInfo) return;
        
        fetch(`/api/ticket-info?category=${encodeURIComponent(category)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.available && data.ticket_type) {
                // Update quota display only
                updateQuotaDisplay(data.ticket_type);
                
                // Check if quota is available
                const remainingQuota = parseInt(data.ticket_type.remaining_quota) || 0;
                isQuotaAvailable = remainingQuota > 0;
                
                // Update submit button state
                updateSubmitButtonState();
            } else {
                // Quota might be exhausted
                isQuotaAvailable = false;
                updateSubmitButtonState();
            }
        })
        .catch(error => {
            console.error('Error refreshing quota:', error);
        });
    }

    // Update quota display with color coding
    function updateQuotaDisplay(ticketType) {
        const quotaElement = document.querySelector('.remaining-quota');
        if (!quotaElement) return;
        
        const remainingQuota = parseInt(ticketType.remaining_quota) || 0;
        const totalQuota = parseInt(ticketType.total_quota) || 1;
        const percentage = (remainingQuota / totalQuota) * 100;
        
        quotaElement.textContent = remainingQuota;
        
        // Color coding based on quota percentage
        const quotaContainer = quotaElement.parentElement;
        quotaContainer.classList.remove('text-green-600', 'text-orange-600', 'text-red-600', 'text-blue-600');
        
        if (remainingQuota === 0) {
            quotaContainer.classList.add('text-red-600');
            quotaElement.innerHTML = '<strong>HABIS</strong>';
        } else if (percentage > 25) {
            quotaContainer.classList.add('text-green-600');
        } else if (percentage > 10) {
            quotaContainer.classList.add('text-orange-600');
        } else {
            quotaContainer.classList.add('text-red-600');
        }
    }

    // Update submit button state based on quota availability
    function updateSubmitButtonState() {
        const submitBtn = form.querySelector('button[type="submit"]');
        const phoneNumber = whatsappInput.value.trim();
        
        // Check multiple conditions for disabling submit button
        const shouldDisable = 
            // WhatsApp validation failed
            (phoneNumber.length >= 9 && !isValidWhatsApp && whatsappInput.classList.contains('border-red-500')) ||
            // Quota not available
            !isQuotaAvailable ||
            // No category selected
            !currentSelectedCategory;
        
        if (shouldDisable) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            
            if (!isQuotaAvailable) {
                submitBtn.innerHTML = '<i class="fas fa-ban mr-2"></i>Kuota Habis - Registrasi Ditutup';
            } else if (phoneNumber.length >= 9 && !isValidWhatsApp && whatsappInput.classList.contains('border-red-500')) {
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Validasi WhatsApp Diperlukan';
            } else {
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Pilih Kategori Terlebih Dahulu';
            }
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.innerHTML = '<i class="fas fa-running mr-2"></i>Daftar Event Lari';
        }
    }
    // Fetch ticket information for selected category
    function fetchTicketInfo(category) {
        if (isLoadingTicketInfo) {
            return;
        }
        
        isLoadingTicketInfo = true;
        
        fetch(`/api/ticket-info?category=${encodeURIComponent(category)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.available) {
                currentTicketData = data.ticket_type;
                showTicketInfo(data.ticket_type);
                
                // Check quota availability
                const remainingQuota = parseInt(data.ticket_type.remaining_quota) || 0;
                isQuotaAvailable = remainingQuota > 0;
                
                // Update submit button state
                updateSubmitButtonState();
                
                if (data.ticket_type.time_remaining) {
                    startCountdown(data.ticket_type.time_remaining);
                }
            } else {
                showTicketUnavailable(data.message || 'Tiket tidak tersedia untuk kategori ini');
                isQuotaAvailable = false;
                updateSubmitButtonState();
            }
        })
        .catch(error => {
            console.error('Error fetching ticket info:', error);
            showTicketError();
            isQuotaAvailable = false;
            updateSubmitButtonState();
        })
        .finally(() => {
            isLoadingTicketInfo = false;
        });
    }

    // Show ticket information
    function showTicketInfo(ticketType) {
        ticketInfoSection.classList.remove('hidden');
        
        const remainingQuota = parseInt(ticketType.remaining_quota) || 0;
        const quotaColor = remainingQuota === 0 ? 'text-red-600' : 'text-green-600';
        const quotaText = remainingQuota === 0 ? 'HABIS' : remainingQuota;
        
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="ticket-type-info">
                            <h3 class="text-lg font-semibold text-gray-800">${ticketType.name} - ${ticketType.category}</h3>
                            <p class="text-2xl font-bold text-green-600">${ticketType.formatted_price || 'Rp 0'}</p>
                            <div class="ticket-quota hidden ${quotaColor}">
                                <small class="text-gray-600">Kuota tersisa: <span class="remaining-quota font-semibold">${quotaText}</span></small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="countdown-timer">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Sisa Waktu:</h3>
                            <div class="timer-display flex space-x-1">
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-days">0</span>
                                    <span class="text-xs text-gray-600">hari</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-hours">0</span>
                                    <span class="text-xs text-gray-600">jam</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-minutes">0</span>
                                    <span class="text-xs text-gray-600">menit</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded timer-seconds">0</span>
                                    <span class="text-xs text-gray-600">detik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Show ticket unavailable message
    function showTicketUnavailable(message) {
        ticketInfoSection.classList.remove('hidden');
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                ${message}
            </div>
        `;
    }

    // Show ticket error
    function showTicketError() {
        ticketInfoSection.classList.remove('hidden');
        ticketInfoSection.innerHTML = `
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 rounded-full p-3 mr-4">
                    <i class="fas fa-ticket-alt text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Informasi Tiket</h2>
            </div>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Gagal memuat informasi tiket. Silakan coba lagi.
            </div>
        `;
    }

    // Hide ticket information
    function hideTicketInfo() {
        ticketInfoSection.classList.add('hidden');
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
            quotaRefreshInterval = null;
        }
        isQuotaAvailable = true;
        updateSubmitButtonState();
    }

    // Start countdown timer with seconds
    function startCountdown(timeRemaining) {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
        
        if (!timeRemaining || timeRemaining.expired) {
            const timerDisplay = document.querySelector('.timer-display');
            if (timerDisplay) {
                timerDisplay.innerHTML = '<span class="text-red-500">Periode berakhir</span>';
            }
            return;
        }
        
        let days = parseInt(timeRemaining.days) || 0;
        let hours = parseInt(timeRemaining.hours) || 0;
        let minutes = parseInt(timeRemaining.minutes) || 0;
        let seconds = parseInt(timeRemaining.seconds) || 0;
        
        // Initial display
        updateTimerDisplay(days, hours, minutes, seconds);
        
        countdownInterval = setInterval(() => {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
                if (minutes < 0) {
                    minutes = 59;
                    hours--;
                    if (hours < 0) {
                        hours = 23;
                        days--;
                        if (days < 0) {
                            clearInterval(countdownInterval);
                            countdownInterval = null;
                            const timerDisplay = document.querySelector('.timer-display');
                            if (timerDisplay) {
                                timerDisplay.innerHTML = '<span class="text-red-500">Periode berakhir</span>';
                            }
                            return;
                        }
                    }
                }
            }
            
            updateTimerDisplay(days, hours, minutes, seconds);
        }, 1000); // Update every second
    }

    // Update timer display elements including seconds
    function updateTimerDisplay(days, hours, minutes, seconds) {
        const daysElement = document.querySelector('.timer-days');
        const hoursElement = document.querySelector('.timer-hours');
        const minutesElement = document.querySelector('.timer-minutes');
        const secondsElement = document.querySelector('.timer-seconds');
        
        if (daysElement) daysElement.textContent = days;
        if (hoursElement) hoursElement.textContent = hours;
        if (minutesElement) minutesElement.textContent = minutes;
        if (secondsElement) secondsElement.textContent = seconds;
    }

    // Cleanup intervals when page is unloaded
    window.addEventListener('beforeunload', function() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        if (quotaRefreshInterval) {
            clearInterval(quotaRefreshInterval);
        }
    });

    // Initial submit button state check
    updateSubmitButtonState();

    // Emergency contact phone validation
    const emergencyPhoneInput = document.getElementById('emergency_contact_phone');
    if (emergencyPhoneInput) {
        emergencyPhoneInput.addEventListener('input', function() {
            // Remove non-numeric characters except + - and space
            let value = this.value.replace(/[^0-9+\-\s]/g, '');
            
            // Limit to 20 characters to accommodate international formats
            if (value.length > 20) {
                value = value.substring(0, 20);
            }
            
            this.value = value;
        });
        
        emergencyPhoneInput.addEventListener('keypress', function(e) {
            // Allow only numbers, +, -, space, and control keys
            const allowedChars = /[0-9+\-\s]/;
            const controlKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'Home', 'End', 'ArrowLeft', 'ArrowRight'];
            
            if (!allowedChars.test(e.key) && !controlKeys.includes(e.key)) {
                e.preventDefault();
            }
        });
    }
});
</script>

<!-- Location Autocomplete CSS -->
<link rel="stylesheet" href="{{ asset('css/location-autocomplete.css') }}">

<!-- Location Autocomplete JS -->
<script src="{{ asset('js/location-autocomplete.js') }}"></script>

<script>
// Initialize location autocomplete after page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize autocomplete for regency search
    const regencySearchInput = document.getElementById('regency_search');
    
    if (regencySearchInput) {
        const autocomplete = new LocationAutocomplete('#regency_search');
        
        // Listen for location selection
        regencySearchInput.addEventListener('locationSelected', function(e) {
            const selection = e.detail;
            
            // Update hidden fields
            document.getElementById('regency_id').value = selection.id;
            document.getElementById('regency_name').value = selection.name;
            document.getElementById('province_name').value = selection.province_name;
            
            console.log('Location selected:', selection);
        });
    }
});
</script>
    </div>
</div>
@endsection
