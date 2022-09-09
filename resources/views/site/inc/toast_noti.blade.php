@if (Session::has('type') && Session::get('type') == 'success')
    <script>
        var message = "{{ Session::get('flash_message') }}";

        Successnotification(message);
    </script>
@elseif (Session::has('type') && Session::get('type') == 'danger')
    <script>
        var message = "{{ Session::get('flash_message') }}";
        Errornotification(message);
    </script>
@endif
