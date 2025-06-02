<!-- Modal Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-danger" style="background-color: rgba(220, 53, 69, 0.5); margin-top: 2rem; margin-left: 30rem;">
            <div class="modal-body d-flex align-items-center text-white position-relative px-4 py-3">
                <i class="fas fa-exclamation-triangle me-2" style="font-size: 2rem;"></i>
                @if (session('error') && is_iterable(session('error')))
                    @foreach (session('error') as $err)
                        <span style="vertical-align: middle;">{!! $err !!}</span>
                    @endforeach
                @elseif (session('error'))
                    <span style="vertical-align: middle;">{!! session('error') !!}</span>
                @endif
            </div>
        </div>
    </div>
</div>

@if (session('error'))
    <script>
        window.addEventListener('load', function () {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();

            // Optional auto-close after 3 seconds
            setTimeout(() => {
                errorModal.hide();
            }, 1500);
        });
    </script>
@endif
