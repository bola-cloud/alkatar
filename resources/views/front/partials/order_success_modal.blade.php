@php
    $modalData = session('order_success_modal');
    $showModal = !empty($modalData);
    $titleLine1 = $modalData['line1'] ?? __('THANK YOU FOR CHOOSING Hi Speed');
    $titleLine2 = $modalData['line2'] ?? __('Orders arriving at 7 PM will be delivered the following day.');
    $orderNumber = $modalData['order_number'] ?? null;
@endphp

<!-- Order Success Modal -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0" style="background:#fff;padding:30px;">
      <div class="modal-body text-center py-5">
        <div style="width:160px;height:160px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;background:#f6f4ec;border-radius:50%;box-shadow: inset 0 6px 0 rgba(0,0,0,0.03);">
          <!-- checkmark -->
          <svg width="110" height="110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="11" fill="#f1efe7" stroke="#d6d0b9" stroke-width="1"/>
            <path d="M6 12.5l3 3 7-8" stroke="#3aa05a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>

        <h4 class="fw-bold text-success mb-2" style="font-family: 'Poppins', sans-serif;">{!! $titleLine1 !!}</h4>
        <p class="mb-4 text-muted" style="font-size:1.05rem;">{!! $titleLine2 !!}</p>

        @if($orderNumber)
          <p class="small text-muted">{{ __('Order number') }}: <strong>{{ $orderNumber }}</strong></p>
        @endif

        <div class="d-flex justify-content-center gap-3 mt-4">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('CANCEL') }}</button>
          <a href="{{ route('checkout') }}" class="btn btn-success rounded-pill px-4">{{ __('CHECKOUT') }}</a>
        </div>
      </div>
    </div>
  </div>
</div>

@if($showModal)
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      try{
        var myModal = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
        myModal.show();
      }catch(e){console.error(e);}
    });
  </script>
@endif
