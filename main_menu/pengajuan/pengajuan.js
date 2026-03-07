function formatTanggal(tgl) {
    const parts = tgl.split("-");
    if (parts.length === 3) {
        return parts[2] + "-" + parts[1] + "-" + parts[0]; // DD-MM-YYYY
    }
    return tgl;
}

function tampilkanPasien(select) {
    const opt = select.options[select.selectedIndex];
    if (opt.value === "") {
        document.getElementById('hasil-pasien').innerHTML = `
          <tr><td class="label">No Rawat</td><td>:</td><td class="value">-</td>
              <td class="label">No RM</td><td>:</td><td class="value">-</td>
              <td class="label">Nama Pasien</td><td>:</td><td class="value">-</td></tr>
          <tr><td class="label">Tanggal</td><td>:</td><td class="value">-</td>
              <td class="label">Dokter</td><td>:</td><td class="value">-</td>
              <td class="label">Poli</td><td>:</td><td class="value">-</td></tr>
          <tr><td class="label">Status</td><td>:</td><td class="value">-</td>
              <td></td><td></td><td></td><td></td><td></td></tr>
        `;
        return;
    }

    // tampilkan detail pasien
    document.getElementById('hasil-pasien').innerHTML = `
        <tr><td class="label">No Rawat</td><td>:</td><td class="value">${opt.dataset.no_rawat}</td>
            <td class="label">No RM</td><td>:</td><td class="value">${opt.dataset.no_rkm_medis}</td>
            <td class="label">Nama Pasien</td><td>:</td><td class="value">${opt.dataset.nm_pasien}</td></tr>
        <tr><td class="label">Tanggal</td><td>:</td><td class="value">${formatTanggal(opt.dataset.tanggal)}</td>
            <td class="label">Dokter</td><td>:</td><td class="value">${opt.dataset.nm_dokter || '-'}</td>
            <td class="label">Poli</td><td>:</td><td class="value">${opt.dataset.nm_poli || '-'}</td></tr>
        <tr><td class="label">Status</td><td>:</td><td class="value">${opt.dataset.status}</td>
            <td></td><td></td><td></td><td></td><td></td></tr>
    `;

    // isi hidden input agar ikut terkirim
    document.getElementById('no_reg').value = opt.dataset.no_reg || '';
    document.getElementById('no_rawat').value = opt.dataset.no_rawat;
    document.getElementById('no_rkm_medis').value = opt.dataset.no_rkm_medis;
    document.getElementById('nm_pasien').value = opt.dataset.nm_pasien;
    document.getElementById('tgl_registrasi').value = opt.dataset.tanggal;
    document.getElementById('status_lanjut').value = opt.dataset.status;
    document.getElementById('nm_dokter').value = opt.dataset.nm_dokter;
    document.getElementById('nm_poli').value = opt.dataset.nm_poli;
}
