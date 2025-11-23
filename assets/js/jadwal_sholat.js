let citiesData = [];
let currentSchedule = [];

document.addEventListener("DOMContentLoaded", async function () {
  await loadCities();
  $('#citySelect').select2({ placeholder: "Pilih Kota/Kabupaten", allowClear: true, width: '100%' });

  try {
    const cityId = await getUserLocation();
    $("#citySelect").val(cityId).trigger('change');
  } catch (error) {
    console.log('Default ke Medan:', error);
    $("#citySelect").val("0228").trigger('change'); // Default ke Medan
  }

  document.querySelectorAll('.view-option').forEach(button => button.addEventListener('click', handleViewModeChange));
  $("#citySelect").on('change', () => handleSelectionChange(true));
  await handleSelectionChange(true);
  setInterval(updateCountdowns, 1000);
});

async function reverseGeocode(lat, lon) {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10`;
    const response = await fetch(url, { headers: { "User-Agent": "Mozilla/5.0 (compatible; jadwal-sholat/1.0; email@example.com)" }, mode: 'cors' });
    if (!response.ok) throw new Error("Reverse geocoding error " + response.status);
    const data = await response.json();
    return data.address?.county || data.address?.city || data.address?.town || null;
  } catch (err) {
    console.error("Reverse geocoding error:", err);
    return null;
  }
}

function normalizeName(name) {
  return name.toUpperCase().replace(/^(CITY OF\s+|KOTA\s+|KAB\.\s+)/, "").trim();
}

function getUserLocation() {
  return new Promise((resolve, reject) => {
    if (!navigator.geolocation) return reject(new Error('Geolocation tidak didukung'));
    navigator.geolocation.getCurrentPosition(
      async (position) => {
        const { latitude, longitude } = position.coords;
        try {
          const candidateName = await reverseGeocode(latitude, longitude);
          if (candidateName && citiesData.length > 0) {
            const candidateNormalized = normalizeName(candidateName);
            const exactMatches = citiesData.filter(city => normalizeName(city.lokasi) === candidateNormalized);
            if (exactMatches.length > 0) return resolve(exactMatches[0].id);
            const substringMatches = citiesData.filter(city => {
              const lokasiNormalized = normalizeName(city.lokasi);
              return lokasiNormalized.includes(candidateNormalized) || candidateNormalized.includes(lokasiNormalized);
            });
            if (substringMatches.length > 0) resolve(substringMatches[0].id);
            else resolve("0228");
          } else resolve("0228");
        } catch (error) {
          console.log('Gagal reverse geocoding, default ke Medan:', error);
          resolve("0228");
        }
      },
      (error) => {
        console.log('Gagal mendapatkan lokasi, default ke Medan:', error);
        resolve("0228");
      }
    );
  });
}

async function loadCities() {
  const citySelect = document.getElementById("citySelect");
  try {
    const response = await fetch("https://api.myquran.com/v2/sholat/kota/semua");
    const data = await response.json();
    citiesData = data.data;
    citySelect.innerHTML = "";
    data.data.forEach(city => {
      citySelect.innerHTML += `<option value="${city.id}">${city.lokasi}</option>`;
    });
  } catch (error) {
    console.error("Gagal mengambil daftar kota:", error);
  }
}

function handleViewModeChange(e) {
  const viewMode = e.target.getAttribute('data-view');
  const monthlyTable = document.getElementById("monthlyTable");
  const dailySchedule = document.getElementById("dailySchedule");
  const buttons = document.querySelectorAll('.view-option');

  buttons.forEach(btn => btn.classList.remove('active'));
  e.target.classList.add('active');

  if (viewMode === "monthly") {
    monthlyTable.style.display = "block";
    dailySchedule.style.display = "none";
    handleSelectionChange(false);
  } else {
    monthlyTable.style.display = "none";
    dailySchedule.style.display = "block";
    handleSelectionChange(true);
  }
}

async function handleSelectionChange(daily = false) {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const cityId = document.getElementById("citySelect").value;
  const cityName = $("#citySelect").find(':selected').text() || "";
  const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  const monthName = months[parseInt(month) - 1];
  const selectedInfo = document.getElementById("selectedInfo");
  selectedInfo.textContent = `Jadwal Sholat untuk ${cityName} dan Sekitarnya ${daily ? `Tanggal ${today.getDate()} ${monthName} ${year}` : `Bulan ${monthName} Tahun ${year}`}`;

  if (cityId) await fetchPrayerTimes(cityId, year, month, daily);
}

async function fetchPrayerTimes(cityId, year, month, daily = false) {
  try {
    document.getElementById("loadingIndicator").style.display = "block";
    const response = await fetch(`https://api.myquran.com/v2/sholat/jadwal/${cityId}/${year}/${month}`);
    if (!response.ok) throw new Error("HTTP error " + response.status);
    const data = await response.json();
    if (data.status) {
      currentSchedule = data.data.jadwal;
      if (daily) displayDailySchedule(data.data.jadwal);
      else displaySchedule(data.data.jadwal);
    } else throw new Error("Data tidak tersedia");
  } catch (error) {
    console.error("Error fetching jadwal:", error);
    alert("Terjadi kesalahan saat mengambil jadwal sholat");
  } finally {
    document.getElementById("loadingIndicator").style.display = "none";
  }
}

function displaySchedule(schedule) {
  const container = document.getElementById("scheduleContainer");
  container.innerHTML = "";
  const today = new Date();
  const formattedToday = today.getDate();
  schedule.forEach(day => {
    const match = day.tanggal.match(/\d+/);
    const dayNumber = match ? parseInt(match[0]) : NaN;
    const isToday = dayNumber === formattedToday;
    container.innerHTML += `
      <tr class="${isToday ? 'highlight-today' : ''}">
        <td>${day.tanggal}</td>
        <td>${day.imsak}</td>
        <td>${day.subuh}</td>
        <td>${day.terbit}</td>
        <td>${day.dhuha}</td>
        <td>${day.dzuhur}</td>
        <td>${day.ashar}</td>
        <td>${day.maghrib}</td>
        <td>${day.isya}</td>
      </tr>`;
  });
}

function displayDailySchedule(schedule) {
  const container = document.getElementById("dailySchedule");
  const today = new Date();
  const formattedToday = String(today.getDate()).padStart(2, '0');
  const todaySchedule = schedule.find(day => {
    const match = day.tanggal.match(/\d+/);
    return match && match[0] === formattedToday;
  });
  if (todaySchedule) {
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const dayName = days[today.getDay()];
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const monthName = monthNames[today.getMonth()];
    const year = today.getFullYear();
    const formattedDate = `Hari ini, ${dayName}, ${formattedToday} ${monthName} ${year}`;

    const prayers = [
      { name: "Imsak", time: todaySchedule.imsak },
      { name: "Subuh", time: todaySchedule.subuh },
      { name: "Terbit", time: todaySchedule.terbit },
      { name: "Dhuha", time: todaySchedule.dhuha },
      { name: "Dzuhur", time: todaySchedule.dzuhur },
      { name: "Ashar", time: todaySchedule.ashar },
      { name: "Maghrib", time: todaySchedule.maghrib },
      { name: "Isya", time: todaySchedule.isya }
    ];
    let html = `
      <h3 class="text-center mb-3" style="color: var(--color-secondary); animation: fadeIn 1s ease-out;">${formattedDate}</h3>
      <div id="countdownText" class="text-center my-3"></div>
      ${prayers.map(prayer => {
        const isPast = isPastPrayer(prayer.time);
        return `
        <div class="daily-item">
          <div class="daily-name">${prayer.name}</div>
          <div class="d-flex align-items-center">
            <div class="daily-time">${prayer.time}</div>
            ${isPast ? '<i class="fas fa-check text-success ms-2"></i>' : ''}
            <div class="daily-countdown ms-2" id="countdown-${prayer.name}"></div>
          </div>
        </div>
      `}).join('')}
    `;
    container.innerHTML = html;
  }
}

function isPastPrayer(timeStr) {
  const now = new Date();
  const [hours, minutes] = timeStr.split(':').map(Number);
  const prayerTime = new Date(now);
  prayerTime.setHours(hours, minutes, 0, 0);
  return now > prayerTime;
}

function updateCountdowns() {
    const now = new Date();
    const formattedToday = String(now.getDate()).padStart(2, '0');
    const todaySchedule = currentSchedule.find(day => {
      if (!day.tanggal) return false;
      const match = day.tanggal.match(/\d+/);
      return match && match[0] === formattedToday;
    });
  
    if (todaySchedule) {
      const prayers = [
        { name: "Imsak", time: todaySchedule.imsak },
        { name: "Subuh", time: todaySchedule.subuh },
        { name: "Terbit", time: todaySchedule.terbit },
        { name: "Dhuha", time: todaySchedule.dhuha },
        { name: "Dzuhur", time: todaySchedule.dzuhur },
        { name: "Ashar", time: todaySchedule.ashar },
        { name: "Maghrib", time: todaySchedule.maghrib },
        { name: "Isya", time: todaySchedule.isya }
      ];
  
      prayers.sort((a, b) => {
        const [aHours, aMinutes] = a.time.split(':').map(Number);
        const [bHours, bMinutes] = b.time.split(':').map(Number);
        return (aHours * 60 + aMinutes) - (bHours * 60 + bMinutes);
      });
  
      const currentMinutes = now.getHours() * 60 + now.getMinutes();
      let nextPrayer = null;
  
      for (const prayer of prayers) {
        const [hours, minutes] = prayer.time.split(':').map(Number);
        const prayerMinutes = hours * 60 + minutes;
        if (prayerMinutes > currentMinutes) {
          nextPrayer = prayer;
          break;
        }
      }
  
      if (!nextPrayer) {
        const tomorrowSchedule = currentSchedule[0];
        if (tomorrowSchedule) {
          nextPrayer = { name: "Subuh", time: tomorrowSchedule.subuh };
        }
      }
  
      // Reset semua countdown
      prayers.forEach(p => {
        const el = document.getElementById(`countdown-${p.name}`);
        if (el) el.textContent = "";
      });
  
      if (nextPrayer) {
        const [hours, minutes] = nextPrayer.time.split(':').map(Number);
        const prayerTime = new Date(now);
        prayerTime.setHours(hours, minutes, 0, 0);
  
        if (now > prayerTime) {
            prayerTime.setDate(prayerTime.getDate() + 1);
        }
  
        const diffMs = prayerTime - now;
        const hoursLeft = Math.floor(diffMs / (1000 * 60 * 60));
        const minutesLeft = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        const secondsLeft = Math.floor((diffMs % (1000 * 60)) / 1000);
        const countdownEl = document.getElementById(`countdown-${nextPrayer.name}`);
        if (countdownEl) {
            countdownEl.textContent = `(-${hoursLeft.toString().padStart(2, '0')}:${minutesLeft.toString().padStart(2, '0')}:${secondsLeft.toString().padStart(2, '0')})`;
        }
      }
    }
}
