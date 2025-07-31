<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Kolektif Berhasil - Amazing Sultra Run</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body>
<!-- Modern Gradient Background with Animated Patterns -->
<div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-emerald-400/20 to-blue-600/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-purple-400/10 to-pink-600/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    </div>

    <div class="relative z-10 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Ultra Modern Success Hero Section -->
        <div class="relative">
            <!-- Glass Morphism Card -->
            <div class="bg-white/20 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8 transform hover:scale-[1.01] transition-all duration-500">
                <div class="bg-gradient-to-r from-emerald-600 via-blue-600 to-purple-600 px-8 py-16 text-center relative overflow-hidden">
                    <!-- Advanced Background Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <div class="absolute inset-0" style="background-image: 
                            radial-gradient(circle at 25px 25px, white 2px, transparent 0),
                            radial-gradient(circle at 75px 75px, white 1px, transparent 0);
                            background-size: 100px 100px, 50px 50px;">
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-8 left-8 w-3 h-3 bg-white/30 rounded-full animate-ping"></div>
                    <div class="absolute top-16 right-16 w-2 h-2 bg-white/40 rounded-full animate-pulse delay-300"></div>
                    <div class="absolute bottom-12 left-16 w-4 h-4 bg-white/20 rounded-full animate-bounce delay-700"></div>
                    
                    <div class="relative z-10">
                        <!-- Ultra Modern Success Icon -->
                        <div class="inline-flex items-center justify-center w-28 h-28 bg-white/10 backdrop-blur-sm rounded-3xl mb-8 group">
                            <div class="w-20 h-20 bg-gradient-to-br from-white to-white/90 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-all duration-300">
                                <i class="fas fa-check-circle text-emerald-500 text-4xl animate-bounce"></i>
                            </div>
                        </div>
                        
                        <h1 class="text-5xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                            <span class="block text-3xl md:text-4xl font-normal mb-2 text-emerald-100">🎉 Selamat!</span>
                            <span class="bg-gradient-to-r from-white to-emerald-100 bg-clip-text text-transparent">
                                Registrasi Berhasil
                            </span>
                        </h1>
                        
                        <p class="text-white/90 text-xl md:text-2xl max-w-4xl mx-auto leading-relaxed font-light mb-8">
                            Semua peserta telah berhasil didaftarkan untuk 
                            <span class="font-bold text-emerald-200">Amazing Sultra Run 2025</span>
                        </p>
                        
                        <!-- Modern Success Count Badge -->
                        @if (session('success_count'))
                        <div class="inline-flex items-center bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-2xl border border-white/20 shadow-lg hover:bg-white/20 transition-all duration-300">
                            <div class="w-8 h-8 bg-emerald-400 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <span class="font-bold text-xl">{{ session('success_count') }} Peserta Terdaftar</span>
                            <div class="ml-3 w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Glass Morphism Details Card -->
        @if (session('success_message'))
            <div class="bg-white/30 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 mb-8 hover:bg-white/40 transition-all duration-500">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-info-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-6 flex-1">
                        <h3 class="text-3xl font-black bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
                            📋 Detail Registrasi
                        </h3>
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-6 mb-8 border border-white/30">
                            <p class="text-gray-800 text-lg font-medium leading-relaxed">{{ session('success_message') }}</p>
                        </div>
                        
                        @if (session('registration_numbers'))
                            <div class="mt-8">
                                <h4 class="font-bold text-gray-800 mb-6 flex items-center text-xl">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-md">
                                        <i class="fas fa-ticket-alt text-white text-sm"></i>
                                    </div>
                                    🎫 Nomor Registrasi Peserta
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                    @foreach (session('registration_numbers') as $index => $regNumber)
                                        <div class="group relative">
                                            <!-- Modern Card with Glass Effect -->
                                            <div class="bg-white/60 backdrop-blur-sm border border-white/40 hover:bg-white/80 text-gray-800 px-6 py-6 rounded-2xl text-center font-mono font-bold shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105">
                                                <!-- Gradient Border Effect -->
                                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-blue-400 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10 blur-sm"></div>
                                                
                                                <div class="relative z-10">
                                                    <div class="text-xs text-emerald-600 mb-2 font-bold uppercase tracking-wider">Peserta {{ $index + 1 }}</div>
                                                    <div class="flex items-center justify-center mb-2">
                                                        <i class="fas fa-id-card mr-2 text-emerald-600 text-lg"></i>
                                                    </div>
                                                    <div class="text-base font-black text-gray-800 bg-white/80 rounded-lg px-3 py-2">{{ $regNumber }}</div>
                                                </div>
                                                
                                                <!-- Floating Icon -->
                                                <div class="absolute -top-3 -right-3 w-8 h-8 bg-gradient-to-r from-emerald-400 to-blue-400 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-0 group-hover:scale-100">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Ultra Modern Participant Details -->
                        @if (session('successful_users'))
                            <div class="mt-12">
                                <h4 class="font-bold text-gray-800 mb-8 flex items-center text-xl">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                        <i class="fas fa-users text-white text-lg"></i>
                                    </div>
                                    👥 Detail Peserta Terdaftar
                                    <span class="ml-3 bg-gradient-to-r from-emerald-400 to-blue-400 text-white px-4 py-1 rounded-full text-sm font-black">
                                        {{ session('success_count', 0) }} peserta
                                    </span>
                                </h4>
                                
                                <!-- Ultra Modern Mobile Card View -->
                                <div class="block lg:hidden space-y-6">
                                    @foreach (session('successful_users') as $index => $user)
                                    <div class="group relative">
                                        <div class="bg-white/60 backdrop-blur-sm rounded-3xl p-6 border border-white/30 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                                            <!-- Gradient Border Effect -->
                                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-blue-400 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10 blur-sm"></div>
                                            
                                            <div class="relative z-10">
                                                <div class="flex items-start justify-between mb-6">
                                                    <div class="flex items-center">
                                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-2xl flex items-center justify-center text-white font-black mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                            {{ $index + 1 }}
                                                        </div>
                                                        <div>
                                                            <h5 class="font-black text-gray-900 text-lg">{{ $user->name }}</h5>
                                                            <p class="text-sm text-gray-600 font-mono bg-gray-100 inline-block px-3 py-1 rounded-lg mt-1">{{ $user->bib_name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent">
                                                            Rp {{ number_format($user->registration_fee, 0, ',', '.') }}
                                                        </div>
                                                        <div class="text-sm text-gray-600 bg-blue-50 px-2 py-1 rounded-full mt-1">{{ $user->race_category_name }}</div>
                                                    </div>
                                                </div>
                                                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 border border-white/50">
                                                    <div class="flex items-center text-gray-700">
                                                        <i class="fas fa-envelope mr-3 text-emerald-600"></i>
                                                        <span class="font-medium">{{ $user->email }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Ultra Modern Desktop Table View - Clean & Organized -->
                                <div class="hidden lg:block">
                                    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40 overflow-hidden">
                                        <!-- Table Header with Better Structure -->
                                        <div class="bg-gradient-to-r from-emerald-600 via-blue-600 to-purple-600 px-6 py-4">
                                            <h5 class="text-white font-black text-lg flex items-center">
                                                <i class="fas fa-table mr-3"></i>
                                                Daftar Peserta Terdaftar
                                            </h5>
                                        </div>
                                        
                                        <!-- Clean Table Structure -->
                                        <div class="overflow-x-auto">
                                            <table class="w-full table-auto">
                                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                                    <tr>
                                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-16">
                                                            <div class="flex items-center">
                                                                <span class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-white font-black text-xs">#</span>
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-user mr-2 text-gray-500"></i>
                                                                Nama Peserta
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-id-badge mr-2 text-gray-500"></i>
                                                                BIB Number
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                                                Email
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            <div class="flex items-center">
                                                                <i class="fas fa-running mr-2 text-gray-500"></i>
                                                                Kategori
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                                            <div class="flex items-center justify-end">
                                                                <i class="fas fa-money-bill-wave mr-2 text-gray-500"></i>
                                                                Biaya Registrasi
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100">
                                                    @foreach (session('successful_users') as $index => $user)
                                                    <tr class="hover:bg-gradient-to-r hover:from-emerald-50 hover:to-blue-50 transition-all duration-300 group">
                                                        <!-- Number Column -->
                                                        <td class="px-6 py-5 whitespace-nowrap">
                                                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-md group-hover:scale-110 transition-transform duration-300">
                                                                {{ $index + 1 }}
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Name Column -->
                                                        <td class="px-6 py-5">
                                                            <div class="text-lg font-black text-gray-900 leading-tight">
                                                                {{ $user->name }}
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- BIB Column -->
                                                        <td class="px-6 py-5">
                                                            <div class="inline-flex items-center bg-gray-100 px-3 py-2 rounded-lg border border-gray-200">
                                                                <i class="fas fa-id-card mr-2 text-gray-500 text-sm"></i>
                                                                <span class="font-mono font-bold text-gray-800 text-sm">{{ $user->bib_name }}</span>
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Email Column -->
                                                        <td class="px-6 py-5">
                                                            <div class="flex items-center text-gray-700">
                                                                <i class="fas fa-envelope mr-3 text-emerald-500"></i>
                                                                <span class="font-medium truncate max-w-xs">{{ $user->email }}</span>
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Category Column -->
                                                        <td class="px-6 py-5">
                                                            <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-bold bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 border border-blue-200 shadow-sm">
                                                                <i class="fas fa-running mr-2 text-blue-600"></i>
                                                                {{ $user->race_category_name }}
                                                            </span>
                                                        </td>
                                                        
                                                        <!-- Fee Column -->
                                                        <td class="px-6 py-5 text-right">
                                                            <div class="font-black text-xl text-emerald-600">
                                                                Rp {{ number_format($user->registration_fee, 0, ',', '.') }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                
                                                <!-- Clean Total Footer -->
                                                <tfoot class="bg-gradient-to-r from-emerald-50 to-blue-50 border-t-4 border-emerald-400">
                                                    <tr>
                                                        <td colspan="5" class="px-6 py-6 text-right">
                                                            <div class="flex items-center justify-end">
                                                                <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                                                    <i class="fas fa-calculator text-white"></i>
                                                                </div>
                                                                <span class="text-2xl font-black text-gray-800">
                                                                    TOTAL PEMBAYARAN:
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-6 text-right">
                                                            <div class="text-3xl font-black text-emerald-600">
                                                                Rp {{ number_format(session('successful_users') ? array_sum(array_column(session('successful_users'), 'registration_fee')) : 0, 0, ',', '.') }}
                                                            </div>
                                                            <div class="text-sm text-gray-600 mt-1">
                                                                {{ session('success_count', 0) }} peserta terdaftar
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Enhanced Errors Section -->
                        @if (session('errors') && count(session('errors')) > 0)
                            <div class="mt-10">
                                <h4 class="font-bold text-gray-800 mb-6 flex items-center text-lg">
                                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                    </div>
                                    Perhatian - {{ count(session('errors')) }} Peserta Perlu Ditindaklanjuti
                                </h4>
                                <div class="space-y-4">
                                    @foreach (session('errors') as $key => $error)
                                    <div class="bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-400 p-6 rounded-xl shadow-md">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-circle text-orange-600 mr-3"></i>
                                            <div>
                                                <div class="font-bold text-orange-800 text-lg">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                                <div class="text-orange-700 mt-1">{{ $error }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Ultra Modern Next Steps Section -->
        <div class="bg-white/30 backdrop-blur-xl rounded-3xl shadow-2xl p-8 mb-8 border border-white/20 hover:bg-white/40 transition-all duration-500">
            <h3 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-10 flex items-center">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-5 shadow-lg">
                    <i class="fas fa-rocket text-white text-xl"></i>
                </div>
                🚀 Langkah Selanjutnya
            </h3>
            
            <div class="space-y-10">
                <!-- Step 1: Modern WhatsApp Notifications -->
                <div class="flex items-start group">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center mr-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-white font-black text-2xl">1</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 mb-4 text-2xl">
                            <i class="fab fa-whatsapp text-green-600 mr-4 text-3xl"></i>
                            📱 Cek WhatsApp Anda
                        </h4>
                        <p class="text-gray-700 text-xl mb-6 leading-relaxed">
                            Setiap peserta akan menerima <span class="font-black text-emerald-600">2 jenis notifikasi WhatsApp</span> yang berbeda:
                        </p>
                        
                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Individual Messages - Ultra Modern -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/40 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <h5 class="font-black text-blue-800 mb-4 flex items-center text-lg">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-user-circle text-white text-sm"></i>
                                    </div>
                                    💬 Pesan Individual
                                </h5>
                                <ul class="text-blue-700 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-key mr-3 text-blue-500"></i>
                                        <span>Password login untuk akun</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-clipboard-list mr-3 text-blue-500"></i>
                                        <span>Detail data registrasi</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-link mr-3 text-blue-500"></i>
                                        <span>Link aktivasi akun</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Collective Payment - Ultra Modern -->
                            <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-6 border border-white/40 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <h5 class="font-black text-purple-800 mb-4 flex items-center text-lg">
                                    <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-white text-sm"></i>
                                    </div>
                                    💰 Pesan Pembayaran Kolektif
                                </h5>
                                <ul class="text-purple-700 space-y-2">
                                    <li class="flex items-center">
                                        <i class="fas fa-calculator mr-3 text-purple-500"></i>
                                        <span>Total pembayaran grup</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-credit-card mr-3 text-purple-500"></i>
                                        <span>Link pembayaran Xendit</span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-list-ul mr-3 text-purple-500"></i>
                                        <span>Daftar semua peserta</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Ultra Modern Payment -->
                <div class="flex items-start group">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-orange-400 to-red-600 rounded-3xl flex items-center justify-center mr-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-white font-black text-2xl">2</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 mb-4 text-2xl">
                            <i class="fas fa-credit-card text-orange-600 mr-4 text-3xl"></i>
                            💳 Pembayaran Kolektif
                        </h4>
                        <p class="text-gray-700 text-xl mb-6 leading-relaxed">
                            <span class="font-black text-orange-600">SEMUA peserta</span> akan menerima link pembayaran via WhatsApp untuk fleksibilitas maksimal.
                        </p>
                        
                        <div class="bg-white/60 backdrop-blur-sm border-2 border-orange-200 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between flex-wrap gap-6">
                                <div>
                                    <p class="text-orange-800 font-black text-xl mb-2">
                                        <i class="fas fa-clock mr-3 text-2xl"></i>
                                        ⏰ Batas waktu pembayaran: 24 jam
                                    </p>
                                    <p class="text-orange-700 text-lg">
                                        Siapa saja dalam grup bisa melakukan pembayaran
                                    </p>
                                </div>
                                @if (session('successful_users'))
                                <div class="text-right">
                                    <div class="text-4xl font-black bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent mb-1">
                                        Rp {{ number_format(session('successful_users') ? array_sum(array_column(session('successful_users'), 'registration_fee')) : 0, 0, ',', '.') }}
                                    </div>
                                    <div class="text-orange-700 font-bold">Total untuk {{ session('success_count', 0) }} peserta</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Ultra Modern Account Access -->
                <div class="flex items-start group">
                    <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-3xl flex items-center justify-center mr-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <span class="text-white font-black text-2xl">3</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-gray-800 mb-4 text-2xl">
                            <i class="fas fa-user-circle text-green-600 mr-4 text-3xl"></i>
                            🔐 Login ke Akun
                        </h4>
                        <p class="text-gray-700 text-xl mb-6 leading-relaxed">
                            Setelah pembayaran berhasil, semua peserta dapat login dengan 
                            <span class="font-black text-green-600">email dan password</span> yang dikirim via WhatsApp.
                        </p>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-10 py-5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-black text-lg rounded-3xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                            <i class="fas fa-sign-in-alt mr-4 text-xl"></i>
                            🚀 Login Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ultra Modern Important Notes -->
        <div class="bg-white/30 backdrop-blur-xl border border-white/20 rounded-3xl p-8 mb-8 shadow-2xl hover:bg-white/40 transition-all duration-500">
            <h3 class="text-3xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-8 flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-5 shadow-lg">
                    <i class="fas fa-info-circle text-white text-lg"></i>
                </div>
                💡 Informasi Penting
            </h3>
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="flex items-start bg-white/60 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-shipping-fast text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-800 mb-2 text-lg">📦 Pengiriman Jersey</h4>
                            <p class="text-gray-700 leading-relaxed">Jersey akan dikirim ke alamat group leader setelah pembayaran dikonfirmasi</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start bg-white/60 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-users text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-800 mb-2 text-lg">🤝 Aktivasi Bersama</h4>
                            <p class="text-gray-700 leading-relaxed">Semua peserta dalam grup yang sama akan aktif bersamaan setelah pembayaran</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-start bg-white/60 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-credit-card text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-800 mb-2 text-lg">💳 Fleksibilitas Pembayaran</h4>
                            <p class="text-gray-700 leading-relaxed">Siapa saja dalam grup bisa melakukan pembayaran menggunakan link yang sama</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start bg-white/60 backdrop-blur-sm rounded-2xl p-6 hover:bg-white/80 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mr-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-question-circle text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-gray-800 mb-2 text-lg">🆘 Bantuan Customer Service</h4>
                            <p class="text-gray-700 leading-relaxed">Jika ada pertanyaan, hubungi customer service melalui WhatsApp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ultra Modern Action Buttons -->
        <div class="text-center mb-8">
            <div class="flex flex-col sm:flex-row gap-8 justify-center items-center max-w-3xl mx-auto">
                <a href="{{ route('register.kolektif') }}" 
                   class="group w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-gray-600 to-gray-800 text-white font-black text-lg rounded-3xl hover:from-gray-700 hover:to-gray-900 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                    <i class="fas fa-plus mr-4 group-hover:rotate-180 transition-transform duration-300 text-xl"></i>
                    ➕ Daftar Grup Lain
                </a>
                <a href="{{ url('/') }}" 
                   class="group w-full sm:w-auto inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-red-600 to-pink-600 text-white font-black text-lg rounded-3xl hover:from-red-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                    <i class="fas fa-home mr-4 group-hover:bounce text-xl"></i>
                    🏠 Kembali ke Beranda
                </a>
            </div>
            
            <!-- Ultra Modern Footer -->
            <div class="mt-16 text-center">
                <div class="inline-flex items-center bg-white/60 backdrop-blur-xl rounded-3xl px-10 py-6 shadow-2xl border border-white/30 hover:bg-white/80 transition-all duration-500 transform hover:scale-105">
                    <div class="text-6xl mr-6 animate-pulse">🏃‍♂️🏃‍♀️</div>
                    <div>
                        <p class="text-2xl font-black bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent mb-1">
                            ✨ Terima kasih telah mendaftar!
                        </p>
                        <p class="text-gray-600 text-lg font-bold">Amazing Sultra Run 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Ultra Modern CSS Animations and Effects -->
<style>
    /* Advanced Keyframe Animations */
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0,-12px,0);
        }
        70% {
            transform: translate3d(0,-6px,0);
        }
        90% {
            transform: translate3d(0,-3px,0);
        }
    }
    
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
        100% {
            transform: translateY(0px);
        }
    }
    
    @keyframes glow {
        0% {
            box-shadow: 0 0 5px rgba(16, 185, 129, 0.2);
        }
        50% {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }
        100% {
            box-shadow: 0 0 5px rgba(16, 185, 129, 0.2);
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Glass Morphism Effects */
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    /* Modern Hover Effects */
    .modern-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    /* Advanced Button Effects */
    .btn-modern {
        position: relative;
        overflow: hidden;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    /* Custom Animations */
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-glow {
        animation: glow 2s ease-in-out infinite alternate;
    }
    
    .animate-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    
    /* Enhanced Group Hover Effects */
    .group:hover .group-hover\\:bounce {
        animation: bounce 1s ease-in-out;
    }
    
    .group:hover .group-hover\\:float {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Responsive Typography */
    @media (max-width: 640px) {
        .text-responsive-xl {
            font-size: 1.5rem;
        }
        .text-responsive-2xl {
            font-size: 1.75rem;
        }
        .text-responsive-3xl {
            font-size: 2rem;
        }
    }
</style>
</body>
</html>
