<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        // Get current settings
        $settings = [
            'restaurant' => config('settings.restaurant', []),
            'pos' => config('settings.pos', []),
            'receipt' => config('settings.receipt', []),
            'tables' => config('settings.tables', []),
        ];

        // Get halls for table management
        $halls = \App\Models\Hall::all();

        return view('settings.index', compact('settings', 'halls'));
    }

    public function updateRestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update restaurant settings
        $restaurantSettings = [
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'tax_id' => $request->tax_id,
        ];

        $this->saveSettings('restaurant', $restaurantSettings);

        return redirect()->route('settings.index')->with('success', 'Restaurant information updated successfully!');
    }

    public function updatePOS(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_tax' => 'nullable|numeric|min:0|max:30',
            'service_charge' => 'nullable|numeric|min:0|max:20',
            'default_discount' => 'nullable|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'currency_position' => 'required|in:left,right',
            'stock_management' => 'nullable|boolean',
            'low_stock_alert' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update POS settings
        $posSettings = [
            'default_tax' => $request->default_tax ?? 0,
            'service_charge' => $request->service_charge ?? 0,
            'default_discount' => $request->default_discount ?? 0,
            'currency' => $request->currency,
            'currency_position' => $request->currency_position,
            'stock_management' => (bool) $request->stock_management,
            'low_stock_alert' => (bool) $request->low_stock_alert,
        ];

        $this->saveSettings('pos', $posSettings);

        return redirect()->route('settings.index')->with('success', 'POS settings updated successfully!');
    }

    public function updateReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt_header' => 'nullable|string|max:1000',
            'receipt_footer' => 'nullable|string|max:1000',
            'receipt_width' => 'required|in:58,80',
            'print_kitchen_orders' => 'required|in:auto,manual,none',
            'print_customer_copy' => 'required|in:auto,manual',
            'show_tax_details' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update receipt settings
        $receiptSettings = [
            'header' => $request->receipt_header,
            'footer' => $request->receipt_footer,
            'width' => $request->receipt_width,
            'print_kitchen_orders' => $request->print_kitchen_orders,
            'print_customer_copy' => $request->print_customer_copy,
            'show_tax_details' => (bool) $request->show_tax_details,
        ];

        $this->saveSettings('receipt', $receiptSettings);

        return redirect()->route('settings.index')->with('success', 'Receipt settings updated successfully!');
    }

    public function updateTables(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_hall' => 'nullable|exists:halls,id',
            'auto_table_assignment' => 'required|in:enabled,disabled',
            'show_table_status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update table settings
        $tableSettings = [
            'default_hall' => $request->default_hall,
            'auto_assignment' => $request->auto_table_assignment,
            'show_status' => (bool) $request->show_table_status,
        ];

        $this->saveSettings('tables', $tableSettings);

        return redirect()->route('settings.index')->with('success', 'Table settings updated successfully!');
    }

    public function createBackup()
    {
        // Create backup of settings
        $backupData = [
            'restaurant' => config('settings.restaurant', []),
            'pos' => config('settings.pos', []),
            'receipt' => config('settings.receipt', []),
            'tables' => config('settings.tables', []),
            'backup_date' => now()->toDateTimeString(),
        ];

        $filename = 'settings-backup-' . now()->format('Y-m-d-H-i-s') . '.json';
        Storage::put('backups/' . $filename, json_encode($backupData, JSON_PRETTY_PRINT));

        return redirect()->route('settings.index')->with('success', 'Backup created successfully!');
    }

    public function resetSettings()
    {
        // Reset to default settings
        $defaultSettings = [
            'restaurant' => [
                'name' => 'My Restaurant',
                'address' => '',
                'phone' => '',
                'email' => '',
                'website' => '',
                'tax_id' => '',
            ],
            'pos' => [
                'default_tax' => 0,
                'service_charge' => 0,
                'default_discount' => 0,
                'currency' => '₹',
                'currency_position' => 'left',
                'stock_management' => true,
                'low_stock_alert' => true,
            ],
            'receipt' => [
                'header' => '',
                'footer' => 'Thank you for your visit!',
                'width' => 58,
                'print_kitchen_orders' => 'auto',
                'print_customer_copy' => 'auto',
                'show_tax_details' => true,
            ],
            'tables' => [
                'default_hall' => null,
                'auto_assignment' => 'enabled',
                'show_status' => true,
            ],
        ];

        foreach ($defaultSettings as $key => $values) {
            $this->saveSettings($key, $values);
        }

        return redirect()->route('settings.index')->with('success', 'Settings reset to default successfully!');
    }

    private function saveSettings($key, $values)
    {
        // Get current settings
        $settings = config('settings', []);

        // Update specific section
        $settings[$key] = $values;

        // Save to configuration file
        $content = '<?php return ' . var_export($settings, true) . ';';
        file_put_contents(config_path('settings.php'), $content);
    }
}