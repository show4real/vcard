<div>
    @if ($row->appointment_tran_id)
        <span class="badge bg-success">{{ __('messages.appointment.paid')  . ' ' . $row->paid_amount}}</span>
    @else
        <span class="badge bg-primary">{{ __('messages.appointment.free') }}</span>
    @endif
</div>
