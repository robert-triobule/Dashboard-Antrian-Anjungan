let voices = [];

function loadVoices() {
    voices = speechSynthesis.getVoices();
    console.log("Voices loaded:", voices);
}
speechSynthesis.onvoiceschanged = loadVoices;
loadVoices();

function playSuara(text, preferredLang = "id-ID", gender = "") {
    // hentikan suara sebelumnya
    speechSynthesis.cancel();

    const utterance = new SpeechSynthesisUtterance(text);
    utterance.rate = 1;
    utterance.pitch = 1;

    // cari voice sesuai bahasa
    let selectedVoice = voices.find(v => v.lang === preferredLang);

    // filter gender (opsional)
    if (gender && selectedVoice) {
        const genderVoice = voices.find(v =>
            v.lang === preferredLang &&
            v.name.toLowerCase().includes(gender.toLowerCase())
        );
        if (genderVoice) selectedVoice = genderVoice;
    }

    // fallback ke voice pertama
    if (!selectedVoice && voices.length > 0) {
        selectedVoice = voices[0];
        utterance.lang = selectedVoice.lang;
    } else {
        utterance.lang = preferredLang;
    }

    if (selectedVoice) {
        utterance.voice = selectedVoice;
    }

    console.log("Memutar suara:", text, "Voice:", selectedVoice?.name);
    speechSynthesis.speak(utterance);
}
