<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class UserRepository
 */
class SettingRepository extends BaseRepository
{
    public $fieldSearchable = [
        'app_name',
    ];

    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return Setting::class;
    }

    /**
     * @return Builder|Builder[]|Collection|Model
     */
    public function update($input,$userId)
    {
        if (in_array($input, ['prefix_code'])) {
            $input['prefix_code'] = '+'.$input['prefix_code'];
        }
        if (isset($input['affiliation_amount'])) {
            $input['affiliation_amount'] = round($input['affiliation_amount'], 2);
        }

        $inputArr = Arr::except($input, ['_token']);
        if (! isset($input['front_cms_form'])) {

            if (! isset($inputArr['is_front_page'])) {
                $setting = Setting::where('key', 'is_front_page')->first();
                $setting->update(['value' => '0']);
            }
            if (! isset($inputArr['is_cookie_banner'])) {
                $setting = Setting::where('key', 'is_cookie_banner')->first();
                $setting->update(['value' => '0']);
            }

            if (! isset($inputArr['currency_after_amount'])) {
                $setting = Setting::where('key', 'currency_after_amount')->first();
                $setting->update(['value' => '0']);
            }

            if (! isset($inputArr['mobile_validation'])) {
                $setting = Setting::where('key', 'mobile_validation')->first();
                $setting->update(['value' => '0']);
            }

            if (! isset($inputArr['register_enable'])) {
                $setting = Setting::where('key', 'register_enable')->first();
                $setting->update(['value' => '0']);
            }


            if (! isset($inputArr['captcha_enable'])) {
                $setting = Setting::where('key', 'captcha_enable')->first();
                $setting->update(['value' => '0']);
            }


        }

        foreach ($inputArr as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (! $setting) {
                continue;
            }

            if (in_array($key, ['app_logo', 'favicon'])) {
                $this->fileUpload($setting, $value);

                continue;
            }
            if (in_array($key, ['home_page_banner'])) {
                $setting->clearMediaCollection(Setting::FRONTPATH);
                $media = $setting->addMedia($input['home_page_banner'])->toMediaCollection(Setting::FRONTPATH,
                    config('app.media_disc'));
                $setting->update(['value' => $media->getFullUrl()]);

                continue;
            }
            $setting->update(['value' => $value]);
        }

        return $setting;
    }

    public function fileUpload($setting, $file)
    {
        $setting->clearMediaCollection(Setting::PATH);
        $media = $setting->addMedia($file)->toMediaCollection(Setting::PATH, config('app.media_disc'));
        $setting->update(['value' => $media->getFullUrl()]);
    }
}
