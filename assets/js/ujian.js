/* ujian.js
   Handles fullscreen, anti-cheat (visibilitychange/blur), and autosave
*/
$(function () {
	var currentHasilId = null;
	var autosaveInterval = 10000; // 10 seconds
	var autosaveTimer = null;

	function startAutosave() {
		if (autosaveTimer) clearInterval(autosaveTimer);
		autosaveTimer = setInterval(function () {
			saveCurrentAnswer();
		}, autosaveInterval);
	}

	function saveCurrentAnswer() {
		if (!currentHasilId) return;
		var payload = {
			hasil_id: currentHasilId,
			soal_id: $(".soal-container.active").data("soal-id"),
			jawaban: $('input[name="jawaban"]:checked').val() || null,
			waktu_jawab: new Date().toISOString(),
		};
		$.post(
			base_url + "ujian/save_answer",
			payload,
			function (resp) {
				// silent
			},
			"json"
		);
	}

	function goFullscreen() {
		var el = document.documentElement;
		if (el.requestFullscreen) el.requestFullscreen();
		else if (el.mozRequestFullScreen) el.mozRequestFullScreen();
		else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
		else if (el.msRequestFullscreen) el.msRequestFullscreen();
	}

	function exitFullscreen() {
		if (document.exitFullscreen) document.exitFullscreen();
		else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
		else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
		else if (document.msExitFullscreen) document.msExitFullscreen();
	}

	// Anti-cheat: end exam when user leaves fullscreen or visibility changes
	function bindAntiCheat() {
		document.addEventListener("visibilitychange", function () {
			if (document.visibilityState !== "visible") {
				endExam("Ujian selesai karena terdeteksi keluar layar/tab.");
			}
		});
		document.addEventListener("fullscreenchange", function () {
			if (!document.fullscreenElement) {
				endExam("Ujian selesai karena keluar fullscreen.");
			}
		});
		window.addEventListener("blur", function () {
			endExam("Ujian selesai karena fokus berpindah dari jendela.");
		});
		// Prevent back navigation (simple)
		window.onpopstate = function () {
			endExam("Ujian selesai karena navigasi kembali.");
		};
	}

	function endExam(message) {
		// call endpoint to finalize
		if (!currentHasilId) return;
		$.post(
			base_url + "ujian/end",
			{ hasil_id: currentHasilId, reason: message },
			function (resp) {
				alert(resp.message || message);
				location.href = base_url + "siswa";
			},
			"json"
		).fail(function () {
			alert("Gagal mengakhiri ujian otomatis. Silakan hubungi admin.");
			location.href = base_url + "siswa";
		});
	}

	// Expose for page to set current hasil id and start
	window.Ujian = {
		// start(hasil_id, startedAtISO, durationSeconds, tickCallback)
		start: function (hasil_id, startedAt, durationSeconds, tickCallback) {
			currentHasilId = hasil_id;
			goFullscreen();
			bindAntiCheat();
			startAutosave();

			// setup timer
			try {
				var startTs = Date.parse(startedAt);
				if (isNaN(startTs)) startTs = Date.now();
				var endTs = startTs + parseInt(durationSeconds) * 1000;
				var timerInterval = setInterval(function () {
					var now = Date.now();
					var rem = Math.max(0, Math.round((endTs - now) / 1000));
					if (typeof tickCallback === "function") tickCallback(rem);
					if (rem <= 0) {
						clearInterval(timerInterval);
						endExam("Waktu ujian habis.");
					}
				}, 1000);
			} catch (e) {
				// ignore
			}
		},
		saveNow: function () {
			saveCurrentAnswer();
		},
	};
});
