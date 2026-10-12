@if(session('success'))
    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) return;
            if (window.useToast) {
                window.useToast().success("{{ session('success') }}");
            }
        });
    </script>
@endif

@if(session('error'))
    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) return;
            if (window.useToast) {
                window.useToast().error("{{ session('error') }}");
            }
        });
    </script>
@endif

@if(session('status'))
    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) return;
            if (window.useToast) {
                window.useToast().info("{{ session('status') }}");
            }
        });
    </script>
@endif
