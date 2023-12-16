<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ProductBuyRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\VcardProductRepository;

class ProductController extends AppBaseController
{
    /**
     * @var VcardProductRepository
     */
    private $vcardProductRepo;

    public function __construct(VcardProductRepository $vcardProductRepo)
    {
        $this->vcardProductRepo = $vcardProductRepo;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        $input = $request->all();

        $service = $this->vcardProductRepo->store($input);

        return $this->sendResponse($service, __('messages.flash.create_product'));
    }

    public function edit($id): JsonResponse
    {
        $product = Product::with('currency')->where('id', $id)->first();
        if ($product->currency) {
            $product['formatted_amount'] = getCurrencyAmount($product->price, $product->currency->currency_icon);
        }

        return $this->sendResponse($product, 'Product successfully retrieved.');
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::where('id', $id)->first();
        $product->clearMediaCollection(Product::PRODUCT_PATH);
        $product->delete();

        return $this->sendSuccess('Product deleted successfully.');
    }

    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        $input = $request->all();

        $service = $this->vcardProductRepo->update($input, $id);

        return $this->sendResponse($service, __('messages.flash.update_product'));
    }

    public function buy(ProductBuyRequest $request)
    {
        $input = $request->all();
        $product = Product::with('currency')->whereId($input['product_id'])->first();
        $currency = isset($product->currency_id) ? $product->currency->currency_code : Currency::whereId(getUserSettingValue('currency_id', getLogInUser()->id))->first()->currency_code;
        try {
            App::setLocale(Session::get('languageChange_' . $product->vcard->url_alias));
            DB::beginTransaction();

            if ($input['payment_method'] == Product::STRIPE) {
                /** @var VcardProductRepository $repo */
                $repo = App::make(VcardProductRepository::class);

                $result = $repo->productBuySession($input, $product);

                DB::commit();

                return $this->sendResponse([
                    'payment_method' => $input['payment_method'],
                    $result,
                ], __('messages.placeholder.stripe_created'));
            }

            //PayPal
            if ($input['payment_method'] == Product::PAYPAL) {
                if (isset($currency) && !in_array(strtoupper($currency), getPayPalSupportedCurrencies())) {

                    return $this->sendError(__('messages.placeholder.this_currency_is_not_supported'));
                }

                /** @var PaypalController $payPalCont */
                $payPalCont = App::make(PaypalController::class);

                $result = $payPalCont->buyProductOnboard($input, $product);

                DB::commit();

                return $this->sendResponse([
                    'payment_method' => $input['payment_method'],
                    $result,
                ], __('messages.placeholder.paypal_created'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }
}
