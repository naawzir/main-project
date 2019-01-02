@if(session()->has('message'))

	<div data-alert class="success-box">

		<p>{{ session()->get('message') }}</p>

    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function()
            {
                $('.success-box').on('click', function ()
                {
                    this.remove();
                }).fadeOut(8000);
            });
        </script>
    @endpush

@endif
