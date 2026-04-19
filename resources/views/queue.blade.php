<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Antrian Pameran</title>

    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- SweetAlert untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- html2canvas and jsPDF untuk Download JPG/PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        select,
        .datepicker,
        button,
        .btn {
            cursor: pointer !important;
        }

        .container-main {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .panel-custom {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .panel-custom .panel-heading {
            background-color: #e9ecef;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            font-size: 16px;
            padding: 15px 20px;
            color: #495057;
        }

        .panel-custom .panel-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 6px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            height: 40px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 20px;
            padding: 8px 25px;
            font-weight: bold;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(40, 167, 69, 0.4);
        }

        .ticket-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        .ticket-box {
            text-align: center;
            padding: 20px;
            border: 2px dashed #007bff;
            border-radius: 12px;
            background-color: #f0f7ff;
            display: inline-block;
            width: 100%;
            word-wrap: break-word;
            box-sizing: border-box;
        }

        .ticket-box h4 {
            margin-top: 0;
            color: #6c757d;
        }

        .ticket-number {
            font-size: 26px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 2px;
            word-break: break-all;
        }

        .filter-box {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="container container-main">
        <div class="row">
            <!-- Form Pemesanan -->
            <div class="col-md-4">
                <div class="panel panel-default panel-custom">
                    <div class="panel-heading">Form Pesanan Foto & Lukis</div>
                    <div class="panel-body">
                        <form id="formPemesanan">
                            <div class="form-group">
                                <label>Pilih Tanggal Dulu <span class="text-danger">*</span></label>
                                <input type="text" id="tanggal_pesan" name="tanggal_pesan"
                                    class="form-control datepicker" placeholder="-- Pilih Tanggal --" required readonly
                                    style="background-color: #fff;">
                            </div>
                            <div class="form-group">
                                <label>Pilih Stand <span class="text-danger">*</span></label>
                                <select id="kd_stand" name="kd_stand" class="form-control" required disabled>
                                    <option value="" disabled selected>Pilih Stand</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pemesan <span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Pemesan"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                                    required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success" id="btnSubmit">Buat Pesanan</button>
                            </div>
                        </form>

                        <div id="ticketResult" style="display: none;" class="ticket-wrapper">
                            <div id="ticketRender" class="ticket-box">
                                <h4>Nomor Antrean Anda:</h4>
                                <div class="ticket-number" id="lblNomorAntri">FT20230226021</div>
                            </div>
                            <div
                                style="margin-top: 15px; display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-primary" id="btnDownload">
                                    Unduh Tiket (JPG)
                                </button>
                                <button type="button" class="btn btn-danger" id="btnDownloadPdf">
                                    Unduh Tiket (PDF)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Antrian -->
            <div class="col-md-8">
                <div class="panel panel-default panel-custom">
                    <div class="panel-heading">Daftar Antrian</div>
                    <div class="panel-body">
                        <div class="filter-box">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Filter Tanggal:</label>
                                    <input type="text" id="filter_tanggal" class="form-control datepicker"
                                        placeholder="-- Semua Tanggal --" readonly style="background-color: #fff;">
                                </div>
                                <div class="col-md-4">
                                    <label>Filter Stand:</label>
                                    <select id="filter_stand" class="form-control">
                                        <option value="">Semua (Keduanya)</option>
                                        <option value="FT">Foto (FT)</option>
                                        <option value="LK">Lukis (LK)</option>
                                    </select>
                                </div>
                                <div class="col-md-4" style="padding-top: 25px;">
                                    <button type="button" class="btn btn-info" id="btnFilterTabel">Cari</button>
                                    <button type="button" class="btn btn-default" id="btnResetFilter">Reset</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tabelAntrian" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Stand</th>
                                        <th>Tanggal</th>
                                        <th>No. Antri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 3 JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <!-- jQuery UI for Datepicker -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        $(document).ready(function () {

            // Inisialisasi Datepicker
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#tanggal_pesan").datepicker("option", "minDate", 0);

            $('#tanggal_pesan').on('change', function () {
                var selectedDate = $(this).val();
                if (selectedDate) {
                    $('#kd_stand').prop('disabled', false).html('<option>Loading...</option>');
                    $.ajax({
                        url: '/api/stands',
                        type: 'GET',
                        data: { tanggal: selectedDate },
                        success: function (response) {
                            var options = '<option value="" disabled selected>Pilih Stand</option>';
                            $.each(response, function (index, stand) {
                                var status = '';
                                if (stand.quota <= 0) {
                                    status = ' [PENUH]';
                                }
                                options += '<option value="' + stand.kd_stand + '" ' + (stand.quota <= 0 ? 'disabled' : '') + '>' + stand.nama_stand + ' (Sisa Kuota: ' + stand.quota + ')' + status + '</option>';
                            });
                            $('#kd_stand').html(options);
                        },
                        error: function () {
                            $('#kd_stand').html('<option>Gagal Load Data</option>');
                        }
                    });
                } else {
                    $('#kd_stand').prop('disabled', true).html('<option value="" disabled selected>Pilih Stand</option>');
                }
            });

            // Inisialisasi DataTable via AJAX
            var table = $('#tabelAntrian').DataTable({
                "ajax": {
                    "url": "/api/queues",
                    "type": "GET",
                    "data": function (d) {
                        d.tanggal = $('#filter_tanggal').val();
                        d.stand = $('#filter_stand').val();
                    }
                },
                "columns": [
                    { "data": null, "sortable": false },
                    { "data": "nama" },
                    { "data": "kd_stand" },
                    { "data": "tanggal_pesan" },
                    {
                        "data": "nomor_antri",
                        "render": function (data, type, row) {
                            return '<b>' + data + '</b>';
                        }
                    }
                ],
                "order": [],
                "pagingType": "simple_numbers",
                "language": {
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya"
                    }
                }
            });

            table.on('draw.dt search.dt order.dt', function () {
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            $('#btnFilterTabel').on('click', function () {
                table.ajax.reload();
            });

            $('#btnResetFilter').on('click', function () {
                $('#filter_tanggal').val('');
                $('#filter_stand').val('');
                table.ajax.reload();
            });

            // Submit form via AJAX
            $('#formPemesanan').on('submit', function (e) {
                e.preventDefault();

                var btn = $('#btnSubmit');
                btn.prop('disabled', true).text('Memproses...');
                $('#ticketResult').hide();

                $.ajax({
                    url: '/api/queues',
                    type: 'POST',
                    data: {
                        kd_stand: $('#kd_stand').val(),
                        nama: $('#nama').val(),
                        email: $('#email').val(),
                        tanggal_pesan: $('#tanggal_pesan').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        btn.prop('disabled', false).text('Buat Pesanan');
                        if (response.success) {
                            $('#lblNomorAntri').text(response.data.nomor_antri);
                            $('#ticketResult').fadeIn();

                            table.ajax.reload();

                            $('#tanggal_pesan').trigger('change');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Antrean berhasil dibuat.',
                                showConfirmButton: false,
                                timer: 2000
                            });

                            $('#nama').val('');
                            $('#email').val('');
                        }
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).text('Buat Pesanan');

                        var errorMessage = 'Terjadi kesalahan sistem.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Maaf',
                            text: errorMessage
                        });
                    }
                });
            });

            // Download JPG Logic
            $('#btnDownload').on('click', function () {
                var btn = $(this);
                var originalText = btn.text();
                btn.text('Memproses...');

                html2canvas(document.querySelector("#ticketRender")).then(canvas => {
                    var image = canvas.toDataURL("image/jpeg", 1.0);
                    var link = document.createElement('a');
                    link.download = $('#lblNomorAntri').text() + '.jpg';
                    link.href = image;
                    link.click();
                    btn.text(originalText);
                });
            });

            // Download PDF Logic
            $('#btnDownloadPdf').on('click', function () {
                var btn = $(this);
                var originalText = btn.text();
                btn.text('Memproses...');

                html2canvas(document.querySelector("#ticketRender")).then(canvas => {
                    var imgData = canvas.toDataURL('image/jpeg');

                    const { jsPDF } = window.jspdf;
                    var pdf = new jsPDF('p', 'mm', 'a6');
                    var width = pdf.internal.pageSize.getWidth();
                    var height = (canvas.height * width) / canvas.width;

                    pdf.addImage(imgData, 'JPEG', 0, 10, width, height);
                    pdf.save($('#lblNomorAntri').text() + '.pdf');

                    btn.text(originalText);
                });
            });

        });
    </script>
</body>

</html>
