<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAlerts;
use App\Models\AttendanceConfig;
use App\Services\QRCodeService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    use HandlesAlerts;

    public function __construct(
        private QRCodeService $qrCodeService
    ) {}

    /**
     * Display system configuration.
     */
    public function index()
    {
        $configs = AttendanceConfig::all()->keyBy('key');

        return view('config.index', compact('configs'));
    }

    /**
     * Update system configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'check_in_start' => 'required|date_format:H:i',
            'check_in_end' => 'required|date_format:H:i',
            'late_threshold' => 'required|date_format:H:i',
            'check_out_start' => 'required|date_format:H:i',
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:20',
            'academic_year' => 'required|string|max:20',
        ]);

        // Normalize time values to H:i format
        $timeFields = ['check_in_start', 'check_in_end', 'late_threshold', 'check_out_start'];
        foreach ($timeFields as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = substr($validated[$field], 0, 5);
            }
        }

        try {
            foreach ($validated as $key => $value) {
                AttendanceConfig::where('key', $key)->update(['value' => $value]);
            }

            $this->alertSuccess('Konfigurasi sistem berhasil diperbarui.');

            return redirect()->route('dashboard.config.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Refresh QR codes.
     */
    public function refreshQR()
    {
        try {
            $checkInCode = $this->qrCodeService->generateCheckInQRCode();
            $checkOutCode = $this->qrCodeService->generateCheckOutQRCode();

            AttendanceConfig::where('key', 'qr_check_in')->update(['value' => $checkInCode]);
            AttendanceConfig::where('key', 'qr_check_out')->update(['value' => $checkOutCode]);

            $this->alertSuccess('QR Code berhasil diperbarui.');

            return back();
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Display QR codes for public view.
     */
    public function displayQR()
    {
        $checkInQR = AttendanceConfig::where('key', 'qr_check_in')->first();
        $checkOutQR = AttendanceConfig::where('key', 'qr_check_out')->first();

        return view('config.qr-display', compact('checkInQR', 'checkOutQR'));
    }
}
