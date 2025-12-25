<?php

namespace App\Http\Controllers\Traits;

use RealRashid\SweetAlert\Facades\Alert;

/**
 * Trait for handling SweetAlert notifications in controllers.
 */
trait HandlesAlerts
{
    /**
     * Show success alert.
     */
    protected function alertSuccess(string $message, string $title = 'Berhasil'): void
    {
        Alert::success($title, $message);
    }

    /**
     * Show error alert.
     */
    protected function alertError(string $message, string $title = 'Gagal'): void
    {
        Alert::error($title, $message);
    }

    /**
     * Show warning alert.
     */
    protected function alertWarning(string $message, string $title = 'Peringatan'): void
    {
        Alert::warning($title, $message);
    }

    /**
     * Show info alert.
     */
    protected function alertInfo(string $message, string $title = 'Informasi'): void
    {
        Alert::info($title, $message);
    }

    /**
     * Handle exception with error alert.
     */
    protected function alertException(\Exception $e, string $prefix = 'Terjadi kesalahan'): void
    {
        $this->alertError($prefix . ': ' . $e->getMessage());
    }
}
