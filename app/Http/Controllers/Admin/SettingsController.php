<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $jerseySizes = JerseySize::all();
        $raceCategories = RaceCategory::all();
        $bloodTypes = BloodType::all();
        $eventSources = EventSource::all();

        return view('admin.settings.index', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
    }

    // Jersey Sizes Management
    public function storeJerseySize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:jersey_sizes,code',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        JerseySize::create([
            'name' => $request->name,
            'code' => $request->code,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Ukuran jersey berhasil ditambahkan!');
    }

    public function updateJerseySize(Request $request, JerseySize $jerseySize)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:jersey_sizes,code,' . $jerseySize->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $jerseySize->update([
            'name' => $request->name,
            'code' => $request->code,
            'active' => $request->has('active'),
        ]);

        return redirect()->back()->with('success', 'Ukuran jersey berhasil diupdate!');
    }

    public function deleteJerseySize(JerseySize $jerseySize)
    {
        $jerseySize->delete();
        return redirect()->back()->with('success', 'Ukuran jersey berhasil dihapus!');
    }

    // Race Categories Management
    public function storeRaceCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        RaceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Kategori lomba berhasil ditambahkan!');
    }

    public function updateRaceCategory(Request $request, RaceCategory $raceCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $raceCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'active' => $request->has('active'),
        ]);

        return redirect()->back()->with('success', 'Kategori lomba berhasil diupdate!');
    }

    public function deleteRaceCategory(RaceCategory $raceCategory)
    {
        $raceCategory->delete();
        return redirect()->back()->with('success', 'Kategori lomba berhasil dihapus!');
    }

    // Blood Types Management
    public function storeBloodType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:5|unique:blood_types,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        BloodType::create([
            'name' => $request->name,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Golongan darah berhasil ditambahkan!');
    }

    public function updateBloodType(Request $request, BloodType $bloodType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:5|unique:blood_types,name,' . $bloodType->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bloodType->update([
            'name' => $request->name,
            'active' => $request->has('active'),
        ]);

        return redirect()->back()->with('success', 'Golongan darah berhasil diupdate!');
    }

    public function deleteBloodType(BloodType $bloodType)
    {
        $bloodType->delete();
        return redirect()->back()->with('success', 'Golongan darah berhasil dihapus!');
    }

    // Event Sources Management
    public function storeEventSource(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        EventSource::create([
            'name' => $request->name,
            'description' => $request->description,
            'active' => true,
        ]);

        return redirect()->back()->with('success', 'Sumber informasi berhasil ditambahkan!');
    }

    public function updateEventSource(Request $request, EventSource $eventSource)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $eventSource->update([
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active'),
        ]);

        return redirect()->back()->with('success', 'Sumber informasi berhasil diupdate!');
    }

    public function deleteEventSource(EventSource $eventSource)
    {
        $eventSource->delete();
        return redirect()->back()->with('success', 'Sumber informasi berhasil dihapus!');
    }
}
