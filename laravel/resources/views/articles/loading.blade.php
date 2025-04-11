@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const forms = document.querySelectorAll('form[data-loading]');
      forms.forEach(form => {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        const text = button.querySelector('.button-text');

        form.addEventListener('submit', function() {
          button.disabled = true;
          spinner.classList.remove('d-none');
          text.textContent = button.dataset.loadingText || '送信中...';
        });
      });
    });
  </script>
@endpush
