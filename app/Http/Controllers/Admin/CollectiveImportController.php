<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\TicketType;
use App\Services\XenditService;

class CollectiveImportController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Show the collective import form
     */
    public function index()
    {
        // Get available race categories from race_categories table
        $raceCategories = DB::table('race_categories')
            ->where('active', 1)
            ->orderBy('name')
            ->pluck('name');

        return view('admin.collective-import.index', compact('raceCategories'));
    }

    /**
     * Download Excel template for collective import
     */
    public function downloadTemplate()
    {
        // Create template on-the-fly instead of storing it
        return $this->createAndDownloadTemplate();
    }

    /**
     * Process Excel/CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:csv|max:10240', // CSV only for now
            'group_name' => 'required|string|max:100',
            'generate_invoice' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $groupName = $request->input('group_name');
            $generateInvoice = $request->boolean('generate_invoice');
            
            // Read file content
            $rows = $this->parseFile($file);

            if (empty($rows)) {
                return redirect()->back()
                    ->withErrors(['import' => ['No valid data found in file']])
                    ->withInput();
            }

            $importedUsers = [];
            $errors = [];
            $timestamp = time();
            $rowNumber = 2; // Start from row 2 (after header)

            foreach ($rows as $row) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }

                try {
                    $userData = $this->parseRowData($row, $rowNumber, $timestamp);
                    
                    if ($userData['errors']) {
                        $errors = array_merge($errors, $userData['errors']);
                        $rowNumber++;
                        continue;
                    }

                    // Create user
                    $user = User::create($userData['data']);
                    $importedUsers[] = $user;

                    Log::info('User imported via Excel', [
                        'user_id' => $user->id,
                        'group_name' => $groupName,
                        'external_id' => $user->xendit_external_id
                    ]);

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
                
                $rowNumber++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['import' => $errors])
                    ->withInput();
            }

            if (empty($importedUsers)) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['import' => ['No valid data found in Excel file']])
                    ->withInput();
            }

            // Generate collective invoice if requested
            $invoiceUrl = null;
            if ($generateInvoice && count($importedUsers) >= 1) { // No minimum limit for admin
                try {
                    $primaryUser = $importedUsers[0];
                    $invoiceUrl = $this->xenditService->createCollectiveInvoiceForAdmin(
                        $primaryUser,
                        $importedUsers,
                        $groupName
                    );

                    // Update all users with invoice URL
                    foreach ($importedUsers as $user) {
                        $user->update([
                            'xendit_invoice_url' => $invoiceUrl,
                            'payment_status' => 'pending'
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::error('Failed to create collective invoice for imported group', [
                        'error' => $e->getMessage(),
                        'group_name' => $groupName,
                        'user_count' => count($importedUsers)
                    ]);
                    // Don't rollback, just continue without invoice
                }
            }

            DB::commit();

            $message = count($importedUsers) . " participants imported successfully";
            if ($invoiceUrl) {
                $message .= " with collective invoice generated";
            }

            return redirect()->route('admin.collective-import.index')
                ->with('success', $message)
                ->with('imported_count', count($importedUsers))
                ->with('invoice_url', $invoiceUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Excel import failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['import' => ['Import failed: ' . $e->getMessage()]])
                ->withInput();
        }
    }

    /**
     * Parse row data from Excel
     */
    private function parseRowData($row, $rowNumber, $timestamp)
    {
        $errors = [];
        
        // Expected columns: Name, BIB Name, Email, Phone, WhatsApp, Birth Place, Birth Date, Gender, Address, City, Province, Race Category, Jersey Size, Blood Type, Emergency Contact, Emergency Phone, Event Source
        $data = [
            'name' => trim($row[0] ?? ''),
            'bib_name' => trim($row[1] ?? ''),
            'email' => trim($row[2] ?? ''),
            'phone' => trim($row[3] ?? ''),
            'whatsapp_number' => trim($row[4] ?? ''),
            'birth_place' => trim($row[5] ?? ''),
            'birth_date' => $this->parseDate($row[6] ?? ''),
            'gender' => trim($row[7] ?? ''),
            'address' => trim($row[8] ?? ''),
            'regency_name' => trim($row[9] ?? ''),
            'province_name' => trim($row[10] ?? ''),
            'race_category' => trim($row[11] ?? ''),
            'jersey_size' => trim($row[12] ?? ''),
            'blood_type' => trim($row[13] ?? ''),
            'emergency_contact' => trim($row[14] ?? ''),
            'emergency_phone' => trim($row[15] ?? ''),
            'event_source' => trim($row[16] ?? 'Admin Import'),
        ];

        // Basic validation
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'bib_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
            'whatsapp_number' => 'required|string|max:15',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Pria,Wanita',
            'address' => 'required|string|max:500',
            'regency_name' => 'required|string|max:255',
            'province_name' => 'required|string|max:255',
            'race_category' => 'required|string|max:255',
            'jersey_size' => 'required|string|max:10',
            'blood_type' => 'required|string|max:5',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $errors[] = "Row {$rowNumber}: {$error}";
            }
        }

        // Check if email exists
        if (User::where('email', $data['email'])->exists()) {
            $errors[] = "Row {$rowNumber}: Email {$data['email']} already exists";
        }

        // Validate race category exists
        if (!DB::table('race_categories')->where('name', $data['race_category'])->where('active', 1)->exists()) {
            $errors[] = "Row {$rowNumber}: Race category '{$data['race_category']}' not found or inactive";
        }

        // Add additional fields for user creation
        if (empty($errors)) {
            $data = array_merge($data, [
                'password' => Hash::make('password123'), // Default password
                'email_verified_at' => now(),
                'role' => 'user',
                'registration_number' => 'ADMIN-IMPORT-' . $timestamp . '-' . $rowNumber,
                'xendit_external_id' => 'AMAZING-ADMIN-IMPORT-' . $timestamp . '-' . $rowNumber,
                'payment_status' => 'pending',
                'whatsapp_validation_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return [
            'data' => $data,
            'errors' => $errors
        ];
    }

    /**
     * Parse date from Excel
     */
    private function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }

        // Handle Excel date serial number
        if (is_numeric($dateValue)) {
            // Excel date serial to PHP date
            $unixTimestamp = ($dateValue - 25569) * 86400;
            return date('Y-m-d', $unixTimestamp);
        }

        // Handle string date
        try {
            return date('Y-m-d', strtotime($dateValue));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parse uploaded file (CSV, Excel)
     */
    private function parseFile($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        try {
            if ($extension === 'csv') {
                return $this->parseCsvFile($file);
            } else if (in_array($extension, ['xlsx', 'xls'])) {
                // For Excel files, try to convert to CSV first or use simple parsing
                return $this->parseExcelAsText($file);
            }
            
            throw new \Exception("Unsupported file format: {$extension}");
            
        } catch (\Exception $e) {
            Log::error('File parsing failed', [
                'file' => $file->getClientOriginalName(),
                'extension' => $extension,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCsvFile($file)
    {
        $rows = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle !== false) {
            // Skip header row
            $header = fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if (!empty(array_filter($row))) {
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }
        
        return $rows;
    }

    /**
     * Parse Excel file as text (simple approach)
     */
    private function parseExcelAsText($file)
    {
        // For now, ask users to save Excel as CSV
        // This is a limitation but avoids complex dependencies
        throw new \Exception('Please save your Excel file as CSV format and upload again. Excel files require additional PHP extensions that are not available.');
    }

    /**
     * Create and download Excel template
     */
    private function createAndDownloadTemplate()
    {
        // Headers for the template
        $headers = [
            'Name *',
            'BIB Name *',
            'Email *',
            'Phone',
            'WhatsApp Number *',
            'Birth Place *',
            'Birth Date * (YYYY-MM-DD)',
            'Gender * (Pria/Wanita)',
            'Address *',
            'City *',
            'Province *',
            'Race Category *',
            'Jersey Size *',
            'Blood Type *',
            'Emergency Contact *',
            'Emergency Phone *',
            'Event Source'
        ];

        // Sample data with available race categories
        $sampleData = [
            [
                'John Doe',
                'JOHN',
                'john.doe@example.com',
                '081234567890',
                '6281234567890',
                'Jakarta',
                '1990-01-15',
                'Pria',
                'Jl. Sudirman No. 1, Jakarta',
                'Jakarta Pusat',
                'DKI Jakarta',
                '10K',
                'L',
                'O',
                'Jane Doe',
                '081234567891',
                'Admin Import'
            ],
            [
                'Jane Smith',
                'JANE',
                'jane.smith@example.com',
                '081234567892',
                '6281234567892',
                'Surabaya',
                '1992-05-20',
                'Wanita',
                'Jl. Tunjungan No. 5, Surabaya',
                'Surabaya',
                'Jawa Timur',
                '5K',
                'M',
                'A',
                'John Smith',
                '081234567893',
                'Admin Import'
            ],
            [
                'Bob Wilson',
                'BOB',
                'bob.wilson@example.com',
                '081234567894',
                '6281234567894',
                'Bandung',
                '1988-12-10',
                'Pria',
                'Jl. Asia Afrika No. 10, Bandung',
                'Bandung',
                'Jawa Barat',
                '21K',
                'XL',
                'B',
                'Alice Wilson',
                '081234567895',
                'Admin Import'
            ]
        ];

        // Create CSV content (Excel can read CSV files)
        $csvContent = '';
        
        // Add headers
        $csvContent .= implode(',', array_map(function($header) {
            return '"' . str_replace('"', '""', $header) . '"';
        }, $headers)) . "\n";
        
        // Add sample data
        foreach ($sampleData as $row) {
            $csvContent .= implode(',', array_map(function($cell) {
                return '"' . str_replace('"', '""', $cell) . '"';
            }, $row)) . "\n";
        }

        // Return as downloadable CSV file
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="collective-import-template.csv"')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }
}
