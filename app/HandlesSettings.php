<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait HandlesSettings
{
    protected function getSettings()
    {
        return DB::table('general_settings')->first();
    }

    protected function getBrand()
    {
        return $this->getSettings()->site_name ?? null;
    }

    protected function getLogo()
    {
        $logoPath = $this->getSettings()->site_logo ?? null;
        return $logoPath ? Storage::url($logoPath) : null;
    }

    protected function getIcon()
    {
        $iconPath = $this->getSettings()->site_favicon ?? null;
        return $iconPath ? Storage::url($iconPath) : null;
    }

    protected function getEmail()
    {
        return $this->getSettings()->support_email ?? null;
    }

    protected function getPhone()
    {
        return $this->getSettings()->support_phone ?? null;
    }

    protected function getAddress()
    {
        $moreConfigs = json_decode($this->getSettings()->more_configs, true);
        return $moreConfigs['Address'] ?? null;
    }

    protected function getHeader()
    {
        $moreConfigs = json_decode($this->getSettings()->more_configs, true);
        return $moreConfigs['Header'] ?? null;
    }

    protected function updateHeader()
    {
        $settings = $this->getSettings();
        $brand = $this->getBrand();
        $email = $this->getEmail();
        $phone = $this->getPhone();
        $address = $this->getAddress();
        $icon = $this->getLogo();

        if ($icon) {
            $icon = '<img src="' . $icon . '">';
        }

        // Gabung nilai-nilai ke Header
        $newHeaderParts = array_filter([$brand, $email, $phone, $address, $icon,]);

        // Tambahkan kata dengan | 
        $newHeader = implode(' | ', $newHeaderParts);

        // Ambil more_configs yang ada
        $moreConfigs = json_decode($settings->more_configs, true) ?? [];

        // Cek apakah Header berubah
        if ($moreConfigs['Header'] ?? null !== $newHeader) {
            // Update more_configs dengan data baru
            $moreConfigs['Header'] = $newHeader;

            // Update kolom more_configs di database
            DB::table('general_settings')
                ->update(['more_configs' => json_encode($moreConfigs)]);
        }
    }
}
