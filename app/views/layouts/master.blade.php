<!doctype html>
<html>

	<head>
        @yield('head')
	</head>
	
	<body>
		@yield('body')
        <!-- Scripts -->
        <script>
            window.config = {
                public: '<% addslashes(public_path()) %>\\'
            };
        </script>
        <% HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js') %>
        @yield('scripts')
        <!-- Scripts End -->
	</body>
</html>
