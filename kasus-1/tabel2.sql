-- Jumlah semua pekerjaan (GROUP BY)
SELECT pekerjaan, COUNT(*) AS jumlah
FROM karyawan
GROUP BY pekerjaan;

-- Jumlah programmer saja (gunakan WHERE)
SELECT pekerjaan, COUNT(*) AS jumlah
FROM karyawan
WHERE pekerjaan = 'Programmer';

-- Jumlah system analyst saja
SELECT pekerjaan, COUNT(*) AS jumlah
FROM karyawan
WHERE pekerjaan = 'System Analyst';

-- Jumlah UI/UX Designer saja
SELECT pekerjaan, COUNT(*) AS jumlah
FROM karyawan
WHERE pekerjaan = 'UI/UX Designer';

