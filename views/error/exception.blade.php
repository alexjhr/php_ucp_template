<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WhatsApp Panel</title>

	{{-- Google Font: Source Sans Pro --}}
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	{{-- highlight.js --}}
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">

	{{-- icheck bootstrap --}}
	@css('plugins/icheck-bootstrap/icheck-bootstrap.min.css')
	{{-- Theme style --}}
	@css('css/adminlte.min.css')
</head>
<body class="hold-transition login-page d-flex vh-100">
<div class="container">
	{{-- /.login-logo --}}
	<div class="card card-outline card-danger">
		<div class="card-header text-center">
			<span class="h1"><b>WhatsApp</b>Panel</span>
		</div>

		<div class="card-body">
			<p class="text-center">{{ $e->getMessage() }}</p>
			<pre><code class="language-php">{{ $e }}</code></pre>
        </div>
		{{-- /.card-body --}}
	</div>
	{{-- /.card --}}
</div>
{{-- /.login-box --}}

{{-- jQuery --}}
@js('plugins/jquery/jquery.min.js')
{{-- Bootstrap 4 --}}
@js('plugins/bootstrap/js/bootstrap.bundle.min.js')
{{-- highlight.js --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
	document.querySelectorAll('pre code').forEach((el) => {
		hljs.highlightElement(el);
	});
});
</script>
</body>
</html>
