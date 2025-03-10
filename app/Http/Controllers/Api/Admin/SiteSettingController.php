<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settingable_type = request()->query('settingable_type');
        $settingable_id = request()->query('settingable_id');

        $site_setting = SiteSetting::where("settingable_type", $settingable_type)
            ->where("settingable_id", $settingable_id)
            ->get();
        $data = [];
        foreach ($site_setting as $item) {
            if ($item->type == 'image') {
                $data[$item->key] = $item->getFirstMediaUrl();
            } else {
                $data[$item->key] = $item->value;
            }
        }
        return $data;
    }

    
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $this->deleteUselessSiteSettings();

            $settingData = $request->all();

            $settingable_type = $request->input('settingable_type');
            $settingable_id = $request->input('settingable_id');

            foreach (SiteSetting::$keys as $key => $data) {
                if (!array_key_exists($key, $settingData)) {
                    continue;
                }

                $value = $settingData[$key] ?? null;

                if (!$value) {
                    if ($data["type"] != "image") {
                        SiteSetting::where('key', $key)
                            ->where("settingable_type", $settingable_type)
                            ->where("settingable_id", $settingable_id)
                            ->delete();
                    }
                    continue;
                }

                $site_setting = SiteSetting::updateOrCreate([
                    "key" => $key,
                    "settingable_type" => $settingable_type,
                    "settingable_id" => $settingable_id,
                ], [
                    "value" => $data["type"] == "text" ? $value : (($data["type"] == "array") ? json_encode($value) : null),
                    "type" => $data["type"]
                ]);

                if ($data["type"] == "image") {
                    $site_setting->clearMediaCollection();
                    $site_setting->addMedia($value)->toMediaCollection();
                }
            }
        });
    }



    public function deleteUselessSiteSettings(string $settingable_type = null, string $settingable_id = null)
    {
        $site_settings_to_delete = SiteSetting::where("settingable_type", $settingable_type)
            ->where("settingable_id", $settingable_id)
            ->whereNotIn("key", array_keys(SiteSetting::$keys))
            ->get();
        // dd($site_settings_to_delete);
        foreach ($site_settings_to_delete as $site_setting) {
            if ($site_setting->type == "image")
                $site_setting->clearMediaCollection();
            $site_setting->delete();
        }
    }
}
