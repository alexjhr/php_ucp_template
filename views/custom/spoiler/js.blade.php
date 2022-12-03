{{-- clipboard.js --}}
@js('plugins/clipboard/clipboard.min.js')

<script>
	// Enable tooltips ("copied!")
	const tooltipTriggerList = document.querySelectorAll("code");
	[...tooltipTriggerList].map((tooltipTriggerEl) => {
		$(tooltipTriggerEl).tooltip();

		$(tooltipTriggerEl).on("shown.bs.tooltip", () => {
			setTimeout(() => $(tooltipTriggerEl).tooltip('hide'), 1000);
		});
	});
	new ClipboardJS("code");
</script>