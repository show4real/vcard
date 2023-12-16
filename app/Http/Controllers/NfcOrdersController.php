<?php

namespace App\Http\Controllers;

use App\Models\Nfc;
use App\Models\Vcard;
use App\Models\Currency;
use App\Models\NfcOrders;
use Laracasts\Flash\Flash;
use Illuminate\Support\Arr;
use App\Models\NfcCardOrder;
use Illuminate\Http\Request;
use App\Mail\AdminNfcOrderMail;
use App\Mail\NfcOrderStatusMail;
use Illuminate\Support\Facades\DB;
use App\Models\NfcOrderTransaction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\NfcOrderRequest;
use App\Repositories\NfcOrderRepository;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AppBaseController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class NfcOrdersController extends AppBaseController
{
    private NfcOrderRepository $nfcOrderRepository;

    public function __construct(NfcOrderRepository $nfcOrderRepository)
    {
        $this->nfcOrderRepository = $nfcOrderRepository;
    }

    public function index()
    {
        return view('nfc.index');
    }

    public function create()
    {
        $vcards = Vcard::whereTenantId(getLogInTenantId())->where('status', Vcard::ACTIVE)->pluck('name', 'id')->toArray();
        $nfcCards  = Nfc::all()->toArray();
        $paymentTypes = getPaymentGateway();
        $currency = getCurrencyIcon(getSuperAdminSettingValue('default_currency'));

        return view('nfc.order', compact('vcards', 'nfcCards', 'paymentTypes', 'currency'));
    }

    public function getVcardData(Request $request)
    {
        $input = $request->all();

        $vcard = Vcard::with('socialLink')->findOrFail($input['vcardId']);

        $data = [
            'id' => $vcard['id'],
            'first_name' => $vcard['first_name'],
            'last_name' => $vcard['last_name'],
            'email' => $vcard['email'],
            'occupation' => $vcard['occupation'],
            'location' => $vcard['location'],
            'phone' => $vcard['phone'],
            'company' => $vcard['company'],

        ];

        return response()->json(['data' => $data, 'success' => true]);
    }

    public function store(NfcOrderRequest $request)
    {
    try {
        DB::beginTransaction();


        $input = $request->all();
        $input['user_id'] = getLogInUserId();

        if($input['payment_method'] != NfcOrders::RAZOR_PAY){
            $nfcOrder = NfcOrders::create($input);
            $nfcOrder->addMedia($input['logo'])->toMediaCollection(NfcOrders::LOGO_PATH);

            $orderId = $nfcOrder->id;
            $userId = $nfcOrder->user_id;
            $email = $input['email'];
            $nfc = NfcOrders::with('nfcCard')->findOrFail($orderId);
        }


        $currency = getSuperAdminSettingValue('default_currency');

        if (isset($input['payment_method'])) {

            //Stripe
            if ($input['payment_method'] == NfcOrders::STRIPE) {

                $repo = App::make(NfcOrderRepository::class);

                $result = $repo->userCreateSession($orderId, $email, $nfc, $currency);

                DB::commit();

                return $this->sendResponse([
                    'payment_method' => $input['payment_method'],
                    $result,
                ], __('messages.placeholder.stripe_created'));
            }

            // Razor Pay
            if ($input['payment_method'] == NfcOrders::RAZOR_PAY) {

                $nfc = Nfc::get()->find($input['card_type']);

                $repo = App::make(NfcOrderRepository::class);

                $result = $repo->userCreateRazorPaySession($input, $nfc, $currency);

                DB::commit();

                return $this->sendResponse([
                    'payment_method' => $input['payment_method'],
                    $result,
                ], __('messages.nfc.razorpay_session_success'));
            }

            //PayPal
            if ($input['payment_method'] == NfcOrders::PAYPAL) {
                if (isset($currency) && !in_array(
                    strtoupper($currency),
                    getPayPalSupportedCurrencies()
                )) {
                    return $this->sendError(__('messages.placeholder.this_currency_is_not_supported'));
                }

                /** @var PaypalController $payPalCont */
                $payPalCont = App::make(PaypalController::class);

                $result = $payPalCont->nfcOrderOnboard($orderId, $email, $nfc, $currency);

                DB::commit();

                return $this->sendResponse([
                    'payment_method' => $input['payment_method'],
                    $result,
                ], __('messages.placeholder.paypal_created'));
            }

            //manual
            if ($input['payment_method'] == NfcOrders::MANUALLY) {

                NfcOrderTransaction::create([
                    'nfc_order_id' => $orderId,
                    'type' => NfcOrders::MANUALLY,
                    'transaction_id' => null,
                    'amount' => $nfc->nfcCard->price,
                    'user_id' => $userId,
                    'status' => NfcOrders::PENDING,

                ]);

                Mail::to( getSuperAdminSettingValue('email'))->send(new AdminNfcOrderMail($nfcOrder));

                Flash::success(__('messages.nfc.order_placed_success'));
                DB::commit();
                return redirect(route('user.orders'));
            }
        }

    } catch (\Exception $e) {
        DB::rollBack();

        throw new UnprocessableEntityHttpException($e->getMessage());
    }
    }


    public function updatePaymentStatus(NfcOrderTransaction $transaction)
    {
        $transaction->update([
            'status' => NfcOrders::SUCCESS,
        ]);

        return $this->sendSuccess(__('messages.nfc.payment_status_update_success'));
    }

    public function updateOrderStatus(Request $request, NfcOrders $order)
    {
        $status = $request['status'];
        $order->update([
            'order_status' => $status,
        ]);

        Mail::to($order['email'])->send(new NfcOrderStatusMail($order,$status));

        return $this->sendSuccess(__('messages.nfc.order_status_update_success'));
    }

    public function show($nfcOrder)
    {
        $nfcCardOrder = NfcOrders::with('nfcTransaction','vcard','nfcCard','nfcPaymentType')->select('*')->findOrFail($nfcOrder);

        return view('nfc.columns.show', compact('nfcCardOrder'));
    }
}

