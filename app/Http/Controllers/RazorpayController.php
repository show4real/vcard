<?php

namespace App\Http\Controllers;

use Exception;
use Razorpay\Api\Api;
use App\Models\NfcOrders;
use Illuminate\View\View;
use Laracasts\Flash\Flash;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\AffiliateUser;
use App\Mail\AdminNfcOrderMail;
use Illuminate\Http\JsonResponse;
use App\Models\NfcOrderTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SubscriptionRepository;

class RazorpayController extends AppBaseController
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function onBoard(Request $request): JsonResponse
    {
        $data = $this->subscriptionRepository->manageSubscription($request->all());

        $subscription = $data['subscription'];
        $api = new Api(getSelectedPaymentGateway('razorpay_key'), getSelectedPaymentGateway('razorpay_secret'));
        $orderData = [
            'receipt' => 1,
            'amount' => $data['amountToPay'] * 100,
            'currency' => $subscription->plan->currency->currency_code,
            'notes' => [
                'email' => Auth::user()->email,
                'name' => Auth::user()->full_name,
                'subscriptionId' => $subscription->id,
                'amountToPay' => $data['amountToPay'],
            ],
        ];

        session(['payment_type' => request()->get('payment_type')]);

        $razorpayOrder = $api->order->create($orderData);
        $data['id'] = $razorpayOrder->id;
        $data['amount'] = $data['amountToPay'];
        $data['name'] = Auth::user()->full_name;
        $data['email'] = Auth::user()->email;
        $data['contact'] = Auth::user()->contact;

        return $this->sendResponse($data, 'Order created successfully');
    }

    /**
     * @return false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function paymentSuccess(Request $request)
    {
        $input = $request->all();
        Log::info('RazorPay Payment Successfully');
        Log::info($input);
        $api = new Api(getSelectedPaymentGateway('razorpay_key'), getSelectedPaymentGateway('razorpay_secret'));
        if (count($input) && ! empty($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $generatedSignature = hash_hmac(
                    'sha256',
                    $payment['order_id'].'|'.$input['razorpay_payment_id'],
                    getSelectedPaymentGateway('razorpay_secret')
                );
                if ($generatedSignature != $input['razorpay_signature']) {
                    return redirect()->back();
                }
                // Create Transaction Here

                $subscriptionID = $payment['notes']['subscriptionId'];
                $amountToPay = $payment['notes']['amountToPay'];
                $subscription = Subscription::findOrFail($subscriptionID);

                Subscription::findOrFail($subscriptionID)->update(['status' => Subscription::ACTIVE]);
                // De-Active all other subscription
                Subscription::whereTenantId(getLogInTenantId())
                    ->where('id', '!=', $subscriptionID)
                    ->where('status', '!=', Subscription::REJECT)
                    ->update([
                        'status' => Subscription::INACTIVE,
                    ]);

                $transaction = Transaction::create([
                    'tenant_id' => $subscription->tenant_id,
                    'transaction_id' => $payment->id,
                    'type' => session('payment_type'),
                    'amount' => $amountToPay,
                    'status' => Subscription::ACTIVE,
                    'meta' => json_encode($payment->toArray()),
                ]);

                $subscription = Subscription::findOrFail($subscriptionID);
                $subscription->update(['transaction_id' => $transaction->id]);

                AffiliateUser::whereUserId(getLogInUserId())->withoutGlobalScopes()
                    ->update(['is_verified' => 1]);

                return view('sadmin.plans.payment.paymentSuccess');
            } catch (Exception $e) {
                return false;
            }
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function paymentFailed(): View
    {
        return view('sadmin.plans.payment.paymentcancel');
    }

    public function nfcPaymentSuccess(Request $request){
        $input = $request->all();

        $api = new Api(getSelectedPaymentGateway('razorpay_key'), getSelectedPaymentGateway('razorpay_secret'));

        if (count($input) && ! empty($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                                $generatedSignature = hash_hmac(
                    'sha256',
                    $payment['order_id'].'|'.$input['razorpay_payment_id'],
                    getSelectedPaymentGateway('razorpay_secret')
                );

                if ($generatedSignature != $input['razorpay_signature']) {
                    return redirect()->back();
                }

    $nfcOrder = NfcOrders::create([
                    'name' => $payment['notes']['customer_name'],
                    'designation' => $payment['notes']['designation'],
                    'phone' => $payment['notes']['phone'],
                    'email' => $payment['notes']['email'],
                    'address' => $payment['notes']['address'],
                    'company_name' => $payment['notes']['company_name'],
                    'order_status' => NfcOrders::PENDING,
                    'card_type' => $payment['notes']['card_type'],
                    'user_id' => getLogInUserId(),
                    'vcard_id' => $payment['notes']['vcard_id'],

                ]);

                NfcOrderTransaction::create([
                    'nfc_order_id' => $nfcOrder->id,
                    'type' => NfcOrders::RAZOR_PAY,
                    'transaction_id' => $payment->id,
                    'amount' => $payment['notes']['amountToPay'],
                    'user_id' => getLogInUser()->id,
                    'status' => NfcOrders::SUCCESS,
                ]);

                Mail::to(getSuperAdminSettingValue('email'))->send(new AdminNfcOrderMail($nfcOrder));

                Flash::success("Order Placed Successfully");

                return redirect(route('user.orders'));
            } catch (Exception $e) {
                Log::info($e->getMessage());
                return false;
            }
        }

        return redirect()->back();
    }

    public function nfcPaymentFailed(Request $request): View
    {
        $input = $request->all();
        $api = new Api(getSelectedPaymentGateway('razorpay_key'), getSelectedPaymentGateway('razorpay_secret'));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        $nfcOrder = NfcOrders::create([
            'name' => $payment['notes']['customer_name'],
            'designation' => $payment['notes']['designation'],
            'phone' => $payment['notes']['phone'],
            'email' => $payment['notes']['email'],
            'address' => $payment['notes']['address'],
            'company_name' => $payment['notes']['company_name'],
            'order_status' => NfcOrders::PENDING,
            'card_type' => $payment['notes']['card_type'],
            'user_id' => getLogInUserId(),
            'vcard_id' => $payment['notes']['vcard_id'],

        ]);

        NfcOrderTransaction::create([
            'nfc_order_id' => $nfcOrder->id,
            'type' => NfcOrders::RAZOR_PAY,
            'transaction_id' => $payment->id,
            'amount' => $payment['notes']['amountToPay'],
            'user_id' => getLogInUser()->id,
            'status' => NfcOrders::FAIL,
        ]);

        return view('sadmin.plans.payment.paymentcancel');
    }
}
