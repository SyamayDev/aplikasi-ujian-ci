// admin-modals.js
(function ($) {
	$(function () {
		// open paket edit modal
		$(document).on("click", ".btn-edit-paket", function (e) {
			e.preventDefault();
			var url = $(this).attr("href");
			$.get(url, function (html) {
				$("#modalContainer").html(html);
				var modalEl = document.getElementById("modalEditPaket");
				var modal = new bootstrap.Modal(modalEl);
				modal.show();
				// bind submit
				$("#formEditPaket").submit(function (ev) {
					ev.preventDefault();
					var f = $(this);
					$.post(
						f.attr("action"),
						f.serialize(),
						function (resp) {
							if (resp && resp.status == "ok") {
								Swal.fire(
									"Berhasil",
									resp.message || "Perubahan disimpan",
									"success"
								).then(function () {
									location.reload();
								});
							} else {
								Swal.fire(
									"Gagal",
									resp.message || "Terjadi kesalahan",
									"error"
								);
							}
						},
						"json"
					).fail(function () {
						Swal.fire("Gagal", "Tidak dapat menyimpan perubahan", "error");
					});
				});
			});
		});

		// open room edit modal
		$(document).on("click", ".btn-edit-room", function (e) {
			e.preventDefault();
			var url = $(this).attr("href");
			$.get(url, function (html) {
				$("#modalContainer").html(html);
				var modalEl = document.getElementById("modalEditRoom");
				var modal = new bootstrap.Modal(modalEl);
				modal.show();
				$(".select2").select2({ theme: "bootstrap-5" });
				$("#formEditRoom").submit(function (ev) {
					ev.preventDefault();
					var f = $(this);
					$.post(
						f.attr("action"),
						f.serialize(),
						function (resp) {
							if (resp && resp.status == "ok") {
								Swal.fire(
									"Berhasil",
									resp.message || "Perubahan disimpan",
									"success"
								).then(function () {
									location.reload();
								});
							} else {
								Swal.fire(
									"Gagal",
									resp.message || "Terjadi kesalahan",
									"error"
								);
							}
						},
						"json"
					).fail(function () {
						Swal.fire("Gagal", "Tidak dapat menyimpan perubahan", "error");
					});
				});
			});
		});
	});
})(jQuery);
