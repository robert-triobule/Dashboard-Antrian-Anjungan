// Update jam digital
function updateClock() {
    const now = new Date();
    const options = {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    };
    document.getElementById("clock").innerText = now.toLocaleDateString("id-ID", options);
}
setInterval(updateClock, 1000);
updateClock();

// Countdown untuk waktu sholat
function startPrayerCountdown(nextPrayer, prayerNames, windowMinutes = 5) {
    const countdownElement = document.getElementById("next-prayer");

    function updateCountdown() {
        const now = new Date();
        const [h, m] = nextPrayer.time.split(":").map(Number);
        const target = new Date();
        target.setHours(h, m, 0, 0);
        const diffMs = target - now;

        if (diffMs > 0 && diffMs <= windowMinutes * 60000) {
            // countdown hanya muncul 5 menit sebelum adzan
            const minutes = Math.floor(diffMs / 60000);
            const seconds = Math.floor((diffMs % 60000) / 1000);
            countdownElement.textContent =
                `⏳ ${nextPrayer.name} dalam ${minutes} menit ${seconds} detik`;
        } else if (diffMs <= 0 && diffMs > -windowMinutes * 60000) {
            // sudah masuk, dalam window 5 menit
            countdownElement.textContent = `🕌 ${nextPrayer.name} sudah masuk`;
        } else {
            // default: tampilkan jam sholat berikutnya
            countdownElement.textContent =
                `🕌 ${nextPrayer.name} — ${nextPrayer.time} WIB`;
        }
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
}

// Ambil jadwal sholat dari API
async function loadNextPrayer() {
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const day = today.getDate();
    try {
        const response = await fetch(`https://api.aladhan.com/v1/timingsByCity/${day}-${month}-${year}?city=Malang&country=Indonesia&method=20`);
        if (!response.ok) throw new Error("API tidak merespons");

        const data = await response.json();

        if (!data || !data.data || !data.data.timings) {
            document.getElementById("next-prayer").textContent = "Jadwal sholat tidak tersedia";
            return;
        }

        const times = data.data.timings;
        const prayerOrder = ["Fajr", "Dhuhr", "Asr", "Maghrib", "Isha"];
        const prayerNames = { Fajr: "Subuh", Dhuhr: "Dzuhur", Asr: "Ashar", Maghrib: "Maghrib", Isha: "Isya" };

        const nowMinutes = today.getHours() * 60 + today.getMinutes();
        let next = null;

        for (const key of prayerOrder) {
            const raw = times[key];
            if (!raw) continue;
            const [h, m] = raw.split(":").map(Number);
            if (isNaN(h) || isNaN(m)) continue;

            const total = h * 60 + m;
            if (total > nowMinutes) {
                next = { name: prayerNames[key], time: raw };
                break;
            }
        }

        if (!next) {
            // fallback ke Subuh besok
            const raw = times["Fajr"] || "00:00";
            next = { name: "Subuh", time: raw };
        }

        // Jalankan countdown dengan logika baru
        startPrayerCountdown(next, prayerNames);

    } catch (e) {
        document.getElementById("next-prayer").textContent = "Gagal memuat jadwal sholat";
    }
}
loadNextPrayer();
setInterval(loadNextPrayer, 60000);
