<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        // Load saved settings from DB
        $settings = [
            'app_name' => Setting::get('app_name', config('app.name')),
            'app_logo' => Setting::get('app_logo', null),
            'timezone' => Setting::get('timezone', config('app.timezone')),
            'default_min_stock' => Setting::get('default_min_stock', config('stockify.default_min_stock', 1)),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
            'timezone' => 'required|string|max:50',
            'default_min_stock' => 'required|integer|min:1',
            'app_logo' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Save values to settings table
        Setting::set('app_name', $request->input('app_name'));
        Setting::set('timezone', $request->input('timezone'));
        Setting::set('default_min_stock', (string) $request->input('default_min_stock'));
        // Handle remove logo request
        if ($request->filled('remove_logo') && $request->input('remove_logo')) {
            $existing = Setting::get('app_logo');
            if ($existing) {
                try {
                    Storage::disk('public')->delete($existing);
                } catch (\Exception $e) {
                    Log::warning('Failed deleting old logo: ' . $e->getMessage());
                }
                Setting::set('app_logo', null);
            }
        }

        // Handle logo upload (with optional resize if Intervention Image is available)
        if ($request->hasFile('app_logo') && $request->file('app_logo')->isValid()) {
            $file = $request->file('app_logo');
            // delete existing first
            $existing = Setting::get('app_logo');
            if ($existing) {
                try {
                    Storage::disk('public')->delete($existing);
                } catch (\Exception $e) {
                    Log::warning('Failed deleting old logo: ' . $e->getMessage());
                }
            }

            $ext = $file->getClientOriginalExtension();
            $filename = 'logo_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $ext;
            $destination = 'settings/' . $filename;

            if (class_exists('\Intervention\Image\ImageManagerStatic')) {
                try {
                    $img = \Intervention\Image\ImageManagerStatic::make($file->getPathname())
                        ->fit(300, 300, function ($constraint) {
                            $constraint->upsize(); });
                    $encoded = (string) $img->encode($ext);
                    Storage::disk('public')->put($destination, $encoded);
                    Setting::set('app_logo', $destination);
                } catch (\Exception $e) {
                    Log::warning('Image processing failed, storing original: ' . $e->getMessage());
                    $path = $file->storeAs('settings', $filename, 'public');
                    Setting::set('app_logo', $path);
                }
            } else {
                // fallback: store original
                $path = $file->storeAs('settings', $filename, 'public');
                Setting::set('app_logo', $path);
            }
        }

        // Optionally update config cache (left to admin)
        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
