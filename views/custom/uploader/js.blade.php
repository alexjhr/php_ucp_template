<script>
	$(function () {
		Dropzone.autoDiscover = false;

		let previewNode = document.querySelector("#template");
		previewNode.id = "";

		let previewTemplate = previewNode.parentNode.innerHTML;
		previewNode.parentNode.removeChild(previewNode);

		const myUploader = new Dropzone(document.body, {
			url: "/upload-file",
			thumbnailWidth: 80,
			thumbnailHeight: 80,
			previewTemplate: previewTemplate,
			autoQueue: false,
			previewsContainer: "#previews",
			clickable: ".fileinput-button"
		});

		myUploader.on("addedfile", (file) => {
			$(file.previewElement).find('.start').on('click', () => myUploader.enqueueFile(file));
		});

		myUploader.on("sending", (file) => {
			// Hide the start button
			$(file.previewElement).find('.cancel').hide();
			$(file.previewElement).find('.start').hide();
		});

		myUploader.on("success", (file) => {
			let pagelocation = $('.nav-link.active').last().attr('href');
			let link = window.location.href.replace(pagelocation, '/public/uploads/') + file.name;

			let copyButton = $(file.previewElement).find('.copy');
			copyButton.show();
			copyButton.on('click', () => ClipboardJS.copy(link));
		});
	});
</script>