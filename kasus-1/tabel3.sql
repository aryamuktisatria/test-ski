-- Jumlah semua di kota (GROUP BY)
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
GROUP BY kota;

-- Jumlah di Madrid saja
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
WHERE kota = 'Madrid';

-- Jumlah di Lisbon saja
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
WHERE kota = 'Lisbon';

-- Jumlah di Jakarta saja
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
WHERE kota = 'Jakarta';

-- Jumlah di Paris saja
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
WHERE kota = 'Paris';

-- Jumlah di London saja
SELECT kota, COUNT(*) AS jumlah
FROM karyawan
WHERE kota = 'London';

