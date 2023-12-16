<div class="modal fade" id="buyProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.subscription.buy_product') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {!! Form::open(['id' => 'productBuyForm']) !!}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center d-none" role="alert"
                    id="countryValidationErrorsBox"><i class="fa-solid fa-face-frown me-5"></i>
                </div>

                <div class="mb-3 form-group">
                    {{ Form::label('name', __('messages.common.name') . ' :', ['class' => 'form-label required']) }}
                    {{ Form::text('name', null, ['class' => 'form-control custom-placeholder', 'required', 'placeholder' => __('messages.form.enter_name'), 'id' => 'paypalIntUserName']) }}
                </div>
                <div class="mb-3">
                    {{ Form::label('email', __('messages.common.email') . ' :', ['class' => 'form-label required ']) }}
                    {{ Form::text('email', null, ['class' => 'form-control custom-placeholder', 'required', 'placeholder' => __('messages.form.enter_email'), 'id' => 'paypalIntUserEmail']) }}
                </div>
                <div class="mb-3">
                    {{ Form::label('phone', __('messages.common.phone') . ' :', ['class' => 'form-label']) }}
                    {{ Form::text('phone', null, ['class' => 'form-control custom-placeholder', 'placeholder' => __('messages.form.enter_phone'), 'id' => 'paypalIntUserPhone']) }}
                </div>
                <div class="mb-3">
                    {{ Form::label('address', __('messages.setting.address') . ' :', ['class' => 'form-label required']) }}
                    {{ Form::textarea('address', null, ['class' => 'form-control custom-placeholder', 'placeholder' => __('messages.setting.address'), 'id' => 'address', 'rows' => 2, 'required']) }}
                </div>

                <div class="mb-3">
                    {{ Form::label('payment_method', __('messages.common.payment_methods') . ' :', ['class' => 'form-label required']) }}
                    {{ Form::select('payment_method', \App\Models\Product::PAYMENT_METHOD, null, ['class' => 'form-control custom-placeholder  form-select form-select-solid select2Selector', 'data-control' => 'select2', 'required', 'id' => 'productPaymentMethod', 'placeholder' => __('messages.common.payment_methods')]) }}
                </div>
                <div class="mt-3">
                    {{ Form::label('price', __('messages.common.price') . ':', ['class' => 'form-label']) }} <span
                        class="form-label" id="price"></span>
                </div>

                {{ Form::hidden('product_id', null, ['id' => 'productId']) }}
            </div>
            <div class="modal-footer pt-0 border-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'submit-btn btn btn-primary m-0', 'id' => 'buyProductBtn']) }}
                <button type="button" class="btn btn-secondary my-0 ms-3 me-0"
                    data-bs-dismiss="modal">{{ __('messages.common.discard') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
