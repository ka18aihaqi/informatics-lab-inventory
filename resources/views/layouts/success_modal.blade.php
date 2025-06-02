<!-- Modal Success -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-success" style="background-color: rgba(40, 167, 69, 0.5); margin-top: 2rem; margin-left: 30rem;">
            <div class="modal-body d-flex align-items-center text-white position-relative px-4 py-3">
                <i class="fas fa-check-circle me-2" style="font-size: 2rem;"></i>
                <span style="vertical-align: middle;">{!! session('success') !!}</span>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        window.addEventListener('load', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            // Optional auto-close after 3 seconds
            setTimeout(() => {
                successModal.hide();
            }, 1500);
        });
    </script>
@endif
