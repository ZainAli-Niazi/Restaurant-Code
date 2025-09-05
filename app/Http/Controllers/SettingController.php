<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Show the settings form
     */
    public function index()
    {
        $settings = Setting::getAll();
        
        // Group settings for easier access in views
        $restaurantSettings = Setting::getGroup('restaurant');
        $taxSettings = Setting::getGroup('tax');
        
        return view('settings.index', compact('settings', 'restaurantSettings', 'taxSettings'));
    }

    /**
     * Update restaurant settings
     */
    public function updateRestaurant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'tax_id' => 'nullable|string|max:50',
            'color_id' => 'nullable|string|max:7', // Hex color code validation
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            Setting::setValue('restaurant_logo', $logoPath, 'restaurant');
        }

        // Update other restaurant settings
        Setting::setValue('restaurant_name', $validated['name'], 'restaurant');
        Setting::setValue('restaurant_phone', $validated['phone'], 'restaurant');
        Setting::setValue('restaurant_address', $validated['address'], 'restaurant');
        Setting::setValue('restaurant_email', $validated['email'], 'restaurant');
        Setting::setValue('restaurant_website', $validated['website'], 'restaurant');
        Setting::setValue('restaurant_tax_id', $validated['tax_id'], 'restaurant');
        Setting::setValue('restaurant_color_id', $validated['color_id'], 'restaurant'); // New color setting

        return redirect()->route('settings.index')
            ->with('success', 'Restaurant information updated successfully.');
    }

    /**
     * Update tax settings
     */
    public function updateTax(Request $request)
    {
        $validated = $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
            'service_charge' => 'nullable|numeric|min:0|max:100',
            'tax_type' => 'required|in:inclusive,exclusive',
        ]);

        Setting::setValue('tax_rate', $validated['tax_rate'], 'tax');
        Setting::setValue('service_charge', $validated['service_charge'], 'tax');
        Setting::setValue('tax_type', $validated['tax_type'], 'tax');

        return redirect()->route('settings.index')
            ->with('success', 'Tax settings updated successfully.');
    }

    /**
     * Get a specific setting value (API endpoint)
     */
    public function getSetting($key)
    {
        $value = Setting::getValue($key);
        return response()->json(['value' => $value]);
    }
}