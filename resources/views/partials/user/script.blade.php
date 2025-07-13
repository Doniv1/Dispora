<script>
    var BASE_URL = "{{ url('/') }}";
    var hostUrl = "{{ asset('assets/user/') }}";
    var css_btn_confirm = 'btn btn-primary mx-1';
    var css_btn_cancel = 'btn btn-danger mx-1';
    var csrf_token = "{{ csrf_token() }}";
</script>

<script src="{{ asset('assets/public/plugins/global/plugins.bundle.js'); }}"></script>
<script src="{{ asset('assets/public/js/scripts.bundle.js'); }}"></script>
<script src="{{ asset('assets/public/js/alert/sweetalert2.min.js') }}"></script>

<script src="{{ asset('assets/frontend/js/plugins.js') }}"></script>
<script src="{{ asset('assets/frontend/js/theme.js') }}"></script>
<script src="{{ asset('assets/frontend/js/custom-scroll.js') }}"></script>
<script src="{{ asset('assets/frontend/plugins/aos/js/aos.js') }}"></script>
<script src="{{ asset('assets/frontend/js/partisi.js') }}"></script>

<script src="{{ asset('assets/public/js/mekanik.js') }}"></script>
<script src="{{ asset('assets/public/js/function.js') }}"></script>
<script src="{{ asset('assets/public/js/global.js') }}"></script>
<script src="{{ asset('assets/public/js/alert/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/public/js/select2.min.js') }}"></script>

<script>

  function toAuth(to,from) {
        $(to).removeClass('d-none');
        $(from).addClass('d-none');
  }

  document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".toggle-password").forEach(function(toggle) {
          toggle.addEventListener("click", function() {
              const targetId = this.getAttribute("data-target");
              const input = document.getElementById(targetId);
              const icon = this.querySelector("i");

              if (input.type === "password") {
                  input.type = "text";
                  icon.classList.remove("fa-eye-slash");
                  icon.classList.add("fa-eye");
              } else {
                  input.type = "password";
                  icon.classList.remove("fa-eye");
                  icon.classList.add("fa-eye-slash");
              }
          });
      });
  });

    $(document).ready(function() {
        $('.select2-custom').select2({
            theme: 'bootstrap-5',
            placeholder: $(this).data('placeholder'),
        });
    });
</script>

@stack('script')
