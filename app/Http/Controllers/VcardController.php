<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVcardRequest;
use App\Http\Requests\UpdateVcardRequest;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use App\Models\Currency;
use App\Models\PrivacyPolicy;
use App\Models\Product;
use App\Models\QrcodeEdit;
use App\Models\ScheduleAppointment;
use App\Models\Setting;
use App\Models\SocialIcon;
use App\Models\SocialLink;
use App\Models\TermCondition;
use App\Models\UserSetting;
use App\Models\Vcard;
use App\Models\VcardBlog;
use App\Repositories\VcardRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use JeroenDesloovere\VCard\VCard as VCardVCard;
use Laracasts\Flash\Flash;
use Spatie\Color\Hex;
use Str;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use URL;

class VcardController extends AppBaseController
{
    private VcardRepository $vcardRepository;

    public function __construct(VcardRepository $vcardRepository)
    {
        $this->vcardRepository = $vcardRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): \Illuminate\View\View
    {
        $makeVcard = $this->vcardRepository->checkTotalVcard();

        return view('vcards.index', compact('makeVcard'));
    }

    /**
     * @return Application|Factory|View
     */
    public function template(): \Illuminate\View\View
    {
        return view('sadmin.vcards.index');
    }

    public function download($id): JsonResponse
    {
        $data = Vcard::with('socialLink')->find($id);

        return $this->sendResponse($data, __('messages.flash.vcard_retrieve'));
    }

    /**
     * @return Application|Factory|View
     */
    public function vcards(): \Illuminate\View\View
    {
        $makeVcard = $this->vcardRepository->checkTotalVcard();

        return view('vcards.templates', compact('makeVcard'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $makeVcard = $this->vcardRepository->checkTotalVcard();
        if (! $makeVcard) {
            return redirect(route('vcards.index'));
        }

        $partName = 'basics';

        return view('vcards.create', compact('partName'));
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function store(CreateVcardRequest $request): RedirectResponse
    {
        $input = $request->all();

        $vcard = $this->vcardRepository->store($input);

        Flash::success(__('messages.flash.vcard_create'));

        return redirect(route('vcards.edit', $vcard->id));
    }

    /**
     * @return Application|Factory|View
     */
    public function show($alias, $id = null): \Illuminate\View\View
    {
        $vcard = Vcard::with([
            'businessHours' => function ($query) {
                $query->where('end_time', '!=', '00:00');
            }, 'services', 'testimonials', 'products', 'blogs', 'privacy_policy', 'term_condition', 'user',
        ])->whereUrlAlias($alias)->first();

        $vcardProducts = $vcard->products->sortDesc()->take(6);

        $blogSingle = '';
        if (isset($id)) {
            $blogSingle = VcardBlog::where('id', $id)->first();
        }
        $setting = Setting::pluck('value', 'key')->toArray();
        $vcard_name = $vcard->template->name;
        $url = explode('/', $vcard->location_url);

        $appointmentDetail = AppointmentDetail::where('vcard_id', $vcard->id)->first();

        $userSetting = UserSetting::where('user_id', $vcard->user->id)->pluck('value', 'key')->toArray();

        $currency = '';
        $paymentMethod = null;
        if (count($userSetting) > 0) {
            $currency = Currency::where('id', $userSetting['currency_id'])->first();
            $paymentMethod = getPaymentMethod($userSetting);
        }

        $reqpage = str_replace('/'.$vcard->url_alias, '', \Request::getRequestUri());
        $reqpage = empty($reqpage) ? 'index' : $reqpage;
        $reqpage = preg_replace("/\.$/", '', $reqpage);
        $reqpage = preg_replace('/[0-9]+/', '', $reqpage);
        $reqpage = str_replace('/', '', $reqpage);
        $reqpage = str_contains($reqpage, '?') ? substr($reqpage, 0, strpos($reqpage, '?')) : $reqpage;

        $vcard_name = $vcard_name == 'vcard11' ? 'vcard11.'.$reqpage : $vcard_name;

        $businessDaysTime = [];

        if ($vcard->businessHours->count()) {
            $dayKeys = [1, 2, 3, 4, 5, 6, 7];
            $openDayKeys = [];
            $openDays = [];
            $closeDays = [];

            foreach ($vcard->businessHours as $key => $openDay) {
                $openDayKeys[] = $openDay->day_of_week;
                $openDays[$openDay->day_of_week] = $openDay->start_time.' - '.$openDay->end_time;
            }

            $closedDayKeys = array_diff($dayKeys, $openDayKeys);

            foreach ($closedDayKeys as $closeDayKey) {
                $closeDays[$closeDayKey] = null;
            }

            $businessDaysTime = $openDays + $closeDays;
            ksort($businessDaysTime);
        }
        $customQrCode = QrcodeEdit::whereTenantId($vcard->user->tenant_id)->pluck('value', 'key')->toArray();

        if ($customQrCode == null) {
            $customQrCode['qrcode_color'] = '#000000';
            $customQrCode['background_color'] = '#ffffff';
        }
        $qrcodeColor['qrcodeColor'] = Hex::fromString($customQrCode['qrcode_color'])->toRgb();
        $qrcodeColor['background_color'] = Hex::fromString($customQrCode['background_color'])->toRgb();

        if(empty(getLocalLanguage())){
            $alias = $vcard->url_alias;
            $languageName = $vcard->default_language;
            session(['languageChange_' . $alias => $languageName]);
            setLocalLang(getLocalLanguage());
        }

        if ($vcard->status) {
            return view(
                'vcardTemplates.' . $vcard_name,
                compact(
                    'vcard',
                    'setting',
                    'url',
                    'appointmentDetail',
                    'userSetting',
                    'currency',
                    'paymentMethod',
                    'blogSingle',
                    'businessDaysTime',
                    'customQrCode',
                    'qrcodeColor',
                    'vcardProducts',
                )
            );
        }
        abort('404');
    }

    public function checkPassword(Request $request, Vcard $vcard): JsonResponse
    {
        setLocalLang(checkLanguageSession($vcard->url_alias));

        if (Crypt::decrypt($vcard->password) == $request->password) {
            session(['password_' => '1']);

            return $this->sendSuccess(__('messages.placeholder.password_is_correct'));
        }

        return $this->sendError(__('messages.placeholder.password_invalid'));
    }

    /**
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function edit(Vcard $vcard, Request $request)
    {

        $partName = ($request->part === null) ? 'basics' : $request->part;

        if ($partName !== TermCondition::TERM_CONDITION && $partName !== PrivacyPolicy::PRIVACY_POLICY) {
            if (! checkFeature($partName)) {
                return redirect(route('vcards.edit', $vcard->id));
            }
        }

        $data = $this->vcardRepository->edit($vcard);
        $data['partName'] = $partName;
        $appointmentDetail = AppointmentDetail::where('vcard_id', $vcard->id)->first();
        $privacyPolicy = PrivacyPolicy::where('vcard_id', $vcard->id)->first();
        $termCondition = TermCondition::where('vcard_id', $vcard->id)->first();

        return view('vcards.edit', compact('appointmentDetail', 'privacyPolicy', 'termCondition'))->with($data);
    }

    public function updateStatus(Vcard $vcard): JsonResponse
    {
        $vcard->update([
            'status' => ! $vcard->status,
        ]);

        return $this->sendSuccess(__('messages.flash.vcard_status'));
    }

    public function update(UpdateVcardRequest $request, Vcard $vcard): RedirectResponse
    {
        $request->except('url_alias');
        $input = $request->all();
        $vcard = $this->vcardRepository->update($input, $vcard);

        if ($vcard) {
            Session::flash('success', ' '.__('messages.flash.vcard_update'));
        }

        return redirect()->back();
    }

    public function destroy(Vcard $vcard): JsonResponse
    {
        $termCondition = TermCondition::whereVcardId($vcard->id)->first();

        if (! empty($termCondition)) {
            $termCondition->delete();
        }

        $privacyPolicy = PrivacyPolicy::whereVcardId($vcard->id)->first();

        if (! empty($privacyPolicy)) {
            $privacyPolicy->delete();
        }

        $vcard->clearMediaCollection(Vcard::PROFILE_PATH);
        $vcard->clearMediaCollection(Vcard::COVER_PATH);
        $vcard->delete();

        $data['make_vcard'] = $this->vcardRepository->checkTotalVcard();

        return $this->sendResponse($data, __('messages.flash.vcard_delete'));
    }

    public function getSlot(Request $request): JsonResponse
    {
        $day = $request->get('day');
        $slots = getSchedulesTimingSlot();
        $html = view('vcards.appointment.slot', ['slots' => $slots, 'day' => $day])->render();

        return $this->sendResponse($html, 'Retrieved successfully.');
    }

    public function getSession(Request $request): JsonResponse
    {
        setLocalLang(getLocalLanguage());

        $vcardId = $request->get('vcardId');

        $date = Carbon::createFromFormat('Y-m-d', $request->date);
        $WeekDaySessions = Appointment::where('day_of_week', ($date->dayOfWeek == 0) ? 7 : $date->dayOfWeek)->where('vcard_id', $vcardId)->get();

        if ($WeekDaySessions->count() == 0) {
            return $this->sendError(__('messages.placeholder.there_is_not_available_slot'));
        }

        $bookedAppointments = ScheduleAppointment::where('vcard_id', $vcardId)->get();

        $bookingSlot = [];
        $bookedSlot = [];
        $userId = Vcard::with('user')->find($vcardId)->user->id;
        foreach ($bookedAppointments as $appointment) {
            if ($appointment->date == $request->date) {
                if (getUserSettingValue('time_format', $userId) == UserSetting::HOUR_24) {
                    $bookedSlot[] = date('H:i', strtotime($appointment->from_time)).' - '.date(
                        'H:i',
                        strtotime($appointment->to_time)
                    );
                } else {
                    $bookedSlot[] = date('h:i A', strtotime($appointment->from_time)).' - '
                        .date('h:i A', strtotime($appointment->to_time));
                }
            }
        }

        foreach ($WeekDaySessions as $index => $WeekDaySession) {
            if (getUserSettingValue('time_format', $userId) == UserSetting::HOUR_24) {
                $bookingSlot[] = date('H:i', strtotime($WeekDaySession->start_time)).' - '.date(
                    'H:i',
                    strtotime($WeekDaySession->end_time)
                );
            } else {
                $bookingSlot[] = date('h:i A', strtotime($WeekDaySession->start_time)).' - '.date(
                    'h:i A',
                    strtotime($WeekDaySession->end_time)
                );
            }
        }

        $slots = array_diff($bookingSlot, $bookedSlot);

        if ($slots == null) {
            return $this->sendError(__('messages.placeholder.there_is_not_available_slot'));
        }

        return $this->sendResponse($slots, 'Retrieved successfully.');
    }

    public function language($languageName, $alias)
    {
        session(['languageChange_'.$alias => $languageName]);
        setLocalLang(getLocalLanguage());

        return $this->sendSuccess(__('messages.flash.language_update'));
    }

    /**
     * @return Application|Factory|View
     */
    public function analytics(Vcard $vcard, Request $request): \Illuminate\View\View
    {
        $input = $request->all();
        $data = $this->vcardRepository->analyticsData($input, $vcard);
        $partName = ($request->part === null) ? 'overview' : $request->part;

        return view('vcards.analytic', compact('vcard', 'partName', 'data'));
    }

    public function chartData(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $data = $this->vcardRepository->chartData($input);

            return $this->sendResponse($data, 'Users fetch successfully.');
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function dashboardChartData(Request $request)
    {
        try {
            $input = $request->all();
            $data = $this->vcardRepository->dashboardChartData($input);

            return $this->sendResponse($data, 'Data fetch successfully.');
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function showBlog($alias, $id): \Illuminate\View\View
    {
        setLocalLang(getLocalLanguage());
        $blog = VcardBlog::with('vcard:id,template_id')->whereRelation('vcard', 'url_alias', '=', $alias)
            ->whereRelation('vcard', 'status', '=', 1)
            ->where('id', $id)
            ->firstOrFail();

        return view('vcards.blog', compact('blog'));
    }

    /**
     * @return Application|Factory|View
     */
    public function showPrivacyPolicy($alias, $id)
    {
        $vacrdTemplate = vcard::find($id);
        setLocalLang(getLocalLanguage());
        $privacyPolicy = PrivacyPolicy::with('vcard')->where('vcard_id', $id)->first();
        $termCondition = TermCondition::with('vcard')->where('vcard_id', $id)->first();
        if ($vacrdTemplate->template_id == 11) {
            return redirect()->route('vcard.show.privacy-policy', [$alias, $id]);
            // return view('vcardTemplates.vcard11.portfolio', compact('privacyPolicy', 'alias', 'termCondition'));
        }

        return view('vcards.privacy-policy', compact('privacyPolicy', 'alias', 'termCondition'));
    }

    public function duplicateVcard($id): JsonResponse
    {
        try {
            $vcard = Vcard::with([
                'services', 'testimonials', 'products', 'blogs', 'privacy_policy', 'term_condition', 'socialLink',
            ])->where('id', $id)->first();
            $this->vcardRepository->getDuplicateVcard($vcard);

            return $this->sendSuccess('Duplicate Vcard Create successfully.');
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getUniqueUrlAlias()
    {
        return getUniqueVcardUrlAlias();
    }

    public function checkUniqueUrlAlias($urlAlias)
    {
        $isUniqueUrl = isUniqueVcardUrlAlias($urlAlias);
        if ($isUniqueUrl === true) {
            return $this->sendResponse(['isUnique' => true], 'URL Alias is available to use.');
        }

        $response = ['isUnique' => false, 'usedInVcard' => $isUniqueUrl];

        return $this->sendResponse($response, 'This URL Alias is already in use');
    }

    public function addContact(Vcard $vcard)
    {

        $vcfVcard = new VCardVCard();
        $lastname = $vcard->last_name;
        $firstname = $vcard->first_name;
        $vcfVcard->addName($lastname, $firstname);
        $vcfVcard->addCompany($vcard->company);
        $vcfVcard->addJobtitle($vcard->job_title);

        if (! empty($vcard->email)) {
            $vcfVcard->addEmail($vcard->email);
        }

        if (! empty($vcard->alternative_email)) {
            $vcfVcard->addEmail($vcard->alternative_email, 'EMAIL;type=Alternate Email');
        }

        if (! empty($vcard->phone)) {
            $vcfVcard->addPhoneNumber('+'.$vcard->region_code.$vcard->phone, 'TEL;type=CELL');
        }
        if (! empty($vcard->alternative_phone)) {
            $vcfVcard->addPhoneNumber('+'.$vcard->alternative_region_code.$vcard->alternative_phone, 'TEL;type=Alternate Phone');
        }

        $vcfVcard->addAddress($vcard->location);
        if (! empty($vcard->location_url)) {
            $vcfVcard->addURL($vcard->location_url, 'TYPE=Location URL');
        }

        $socialLinks = SocialLink::whereVcardId($vcard->id)->first()->toArray();
        $customSocialLinks = SocialIcon::with('media')->whereSocialLinkId($socialLinks['id'])->get();
        unset($socialLinks['id']);
        unset($socialLinks['media']);
        unset($socialLinks['created_at']);
        unset($socialLinks['updated_at']);
        unset($socialLinks['social_icon']);
        unset($socialLinks['vcard_id']);

        foreach ($customSocialLinks as $link) {
            $socialLinks = array_merge($socialLinks, [$link->media[0]['name'] => $link->link]);
        }

        foreach ($socialLinks as $key => $link) {
            $name = Str::camel($key);
            $vcfVcard->addURL($link, 'TYPE='.$name);
        }

        $vcfVcard->addURL(URL::to($vcard->url_alias));

        if ($media = $vcard->getMedia(\App\Models\Vcard::PROFILE_PATH)->first()) {
            $vcfVcard->addPhotoContent(file_get_contents($media->getFullUrl()));
        }

        return \Response::make(
            $vcfVcard->getOutput(),
            200,
            $vcfVcard->getHeaders(true)
        );
    }

    public function showProducts($id,$alias){

        $vcard = Vcard::with([
            'businessHours' => function ($query) {
                $query->where('end_time', '!=', '00:00');
            }, 'services', 'testimonials', 'products', 'blogs', 'privacy_policy', 'term_condition', 'user',
        ])->whereUrlAlias($alias)->first();

        $vcardProducts = $vcard->products->sortDesc()->take(6);

        $products =  Product::with('vcard')->whereVcardId($id)->get();
        $template_id = $products->first()->vcard->template_id;

        if ($vcard->status) {
            return view(
                'vcardTemplates/products/vcard'.$template_id,
                compact(
                    'vcard',
                    'vcardProducts',
                    'products',
                )
            );
        }
    }

}
