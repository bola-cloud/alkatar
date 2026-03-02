@php
    $modalData = session('order_error_modal');
    $showModal = !empty($modalData);
    $titleLine1 = $modalData['line1'] ?? __('Ooops! Something went wrong.');
    $titleLine2 = $modalData['line2'] ?? __('Double-check your email and password.');
    $action = $modalData['action'] ?? null;
@endphp

<!-- Order Error Modal -->
<div class="modal fade" id="orderErrorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 border-0" style="background:#fff;padding:30px;">
      <div class="modal-body text-center py-5">
        <div style="width:160px;height:160px;margin:0 auto 20px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:20%;box-shadow: inset 0 6px 0 rgba(0,0,0,0.03);">
          <!-- exclamation triangle -->
          <svg width="110" height="110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 21h22L12 2 1 21z" fill="#fff" stroke="#e11" stroke-width="1.2"/>
            <path d="M12 8v5" stroke="#e11" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M12 15h.01" stroke="#e11" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </div>

        <h4 class="fw-bold text-danger mb-2" style="font-family: 'Poppins', sans-serif;">{!! $titleLine1 !!}</h4>
        <p class="mb-4 text-muted" style="font-size:1.05rem;">{!! $titleLine2 !!}</p>

        <div class="d-flex justify-content-center gap-3 mt-4">
          <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">{{ __('CANCEL') }}</button>
          @if($action)
            <a href="{{ $action }}" class="btn btn-success rounded-pill px-4">{{ __('TRY AGAIN') }}</a>
          @else
            <button type="button" class="btn btn-success rounded-pill px-4" data-bs-dismiss="modal">{{ __('TRY AGAIN') }}</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@if($showModal)
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      try{
        var myModal = new bootstrap.Modal(document.getElementById('orderErrorModal'));
        myModal.show();
      }catch(e){console.error(e);}
    });
  </script>
@endif
