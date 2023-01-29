<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Setting\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Mail\Admin\TestMail;
use App\Models\Setting;
use App\Traits\Helper\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SettingController extends Controller
{
    use UploadFileTrait;

    public function __construct()
    {
        $this->middleware(['permission:Setting list']);
    }

    public function index()
    {
        try {
            $setting = Utility::settings();
            $date = [
                'd-m-Y' => 'dd-mm-yyyy (09-02-2022)',
                'D-m-Y' => 'DD-mm-yyyy (Mon-02-2022)',
                'Y-m-d' => 'yyyy-mm-dd (2022-02-09)',
                'm-d-Y' => 'mm-dd-yyyy (02-09-2022)',
                'M-d-Y' => 'mm-dd-yyyy (02-Feb-2022)',
            ];

            return view('admin.setting.index', compact('setting', 'date'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    private function uploadFiles($name, $setting)
    {
        Setting::query()->updateOrCreate(['key' => $name], [
            'value' => $this->upload([
                'file' => $name,
                'path' => 'setting',
                'upload_type' => 'single',
                'delete_file' => $setting[$name] ?? ''
            ])
        ]);
    }

    public function store(SettingRequest $request)
    {
        $data = $request->validated();

        $setting = Utility::settings();
        if ($request->hasFile('web_logo')) {
            $this->uploadFiles('web_logo', $setting);
        }
        if ($request->hasFile('mobile_logo')) {
            $this->uploadFiles('mobile_logo', $setting);
        }
        if ($request->hasFile('favicon')) {
            $this->uploadFiles('favicon', $setting);
        }

        if ($request->hasFile('icon')) {
            $this->uploadFiles('icon', $setting);
        }
        unset($data['_token'], $data['icon'], $data['web_logo'], $data['mobile_logo'], $data['favicon']);
        foreach ($data as $name => $value) {
            Setting::query()->updateOrCreate(['key' => $name], ['value' => $value]);
        }
        $link_google_play = QrCode::format('png')
            //->merge('path-to-image.png')
            ->size(300)->errorCorrection('H')
            ->generate($request->link_google_play);
        $output_file_link_google_play = '/qrCode/link_google_play.png';

        $link_apple_store = QrCode::format('png')
            //->merge('path-to-image.png')
            ->size(300)->errorCorrection('H')
            ->generate($request->link_apple_store);
        $output_file_link_apple_store = '/qrCode/link_apple_store.png';

        $link_website = QrCode::format('png')
            //->merge('path-to-image.png')
            ->size(300)->errorCorrection('H')
            ->generate($request->link_website);
        $output_file_link_website = '/qrCode/link_website.png';


        Storage::disk('public')->put($output_file_link_google_play, $link_google_play);
        Storage::disk('public')->put($output_file_link_apple_store, $link_apple_store);
        Storage::disk('public')->put($output_file_link_website, $link_website);

        return redirect()->back()->with([
            'success' => _trans('Setting Updated successfully.'),
        ]);
    }

    public function saveEmailSettings(SettingRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $arrEnv = [
                'MAIL_MAILER' => $request->mail_mailer,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_from_name,
                'MAIL_FROM_NAME' => $request->mail_from_address,
            ];
            Utility::setEnvironmentValue($arrEnv);
            return redirect()->back()->with('success', _trans('Setting successfully updated.'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }

    }

    public function testSendMail(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), ['email' => 'required|email']);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            try {
                Mail::to($request->email)->send(new TestMail($request));
            } catch (\Exception $e) {
                $smtp_error = _trans('E-Mail has been not sent due to SMTP configuration');
            }
            return redirect()->back()->with([
                'success', _trans('Email send Successfully.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''),
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }

    public function savePusherSettings(SettingRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $arrEnvStripe = [
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            $envStripe = Utility::setEnvironmentValue($arrEnvStripe);
            if ($envStripe) {
                return redirect()->back()->with('success', _trans('Setting successfully updated.'));
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }
}
