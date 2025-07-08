# Unpaid Registrations UI Migration

## Overview
Migrasi halaman Unpaid Registrations dari Bootstrap ke Tailwind CSS untuk konsistensi dengan design system admin dashboard.

## Changes Made

### 1. Layout Migration
- **From**: `layouts.app` (Bootstrap)
- **To**: `layouts.admin` (Tailwind CSS)

### 2. UI Components Updated

#### Status Cards
- **Old**: Bootstrap cards (`card`, `card-body`, `bg-warning`, `bg-info`, `bg-danger`, `bg-success`)
- **New**: Tailwind cards (`bg-yellow-500`, `bg-blue-500`, `bg-red-500`, `bg-green-500`)

#### Management Controls
- **Old**: Bootstrap button groups (`btn-group`, `btn btn-success`, `btn btn-primary`)
- **New**: Tailwind buttons (`inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md`)

#### Data Table
- **Old**: Bootstrap table (`table`, `table-striped`, `table-hover`)
- **New**: Tailwind table (`min-w-full divide-y divide-gray-200`, `bg-gray-50`, `hover:bg-gray-50`)

### 3. Color Scheme Updated
- **Status Badges**: 
  - Success: `bg-green-100 text-green-800`
  - Warning: `bg-yellow-100 text-yellow-800`
  - Info: `bg-blue-100 text-blue-800`
  - Danger: `bg-red-100 text-red-800`

### 4. Responsive Design
- Grid system: `grid grid-cols-1 md:grid-cols-4 gap-6`
- Table: `overflow-x-auto` for mobile scrolling
- Buttons: `flex flex-wrap gap-3` for responsive layout

### 5. JavaScript Functionality
- All JavaScript functions preserved
- Updated HTML classes to Tailwind equivalents
- Alert styling updated to match Tailwind design

## Features Preserved
- Auto-refresh every 2 minutes
- Real-time status updates
- Force reminders functionality
- Force cleanup functionality
- Queue management integration
- Time-based status indicators

## Files Modified
- `resources/views/admin/unpaid-registrations.blade.php` - Complete rewrite
- `resources/views/admin/unpaid-registrations-old.blade.php` - Backup of old file

## Benefits
- Consistent design with admin dashboard
- Better responsive behavior
- Improved accessibility
- Modern UI components
- Better maintainability

## Layout Structure
```
Admin Layout (Fixed Sidebar)
├── Status Overview Cards (4 columns)
├── Management Controls Section
└── Users Table with Pagination
```

## Color Coding
- **Yellow**: Total unpaid registrations
- **Blue**: Within 6 hours (safe)
- **Red**: Over 6 hours (critical)
- **Green**: Auto cleanup status

## Created: July 8, 2025
