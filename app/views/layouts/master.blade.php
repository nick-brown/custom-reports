<!doctype html>
<html>

	<head>
        @yield('head')
	</head>
	
	<body>
		@yield('body')
        
        <!-- Scripts -->
        {{ HTML::script('js/jquery-2.0.3.min.js') }}
        @yield('scripts')
        <!-- Scripts End -->
	</body>
</html>
