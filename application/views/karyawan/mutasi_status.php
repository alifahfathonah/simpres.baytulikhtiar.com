<div class="page-content" style="min-height: 860px;">
	<div class="theme-panel hidden-xs hidden-sm">
	</div>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="<?= site_url('dashboard'); ?>">Home</a>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<span>Mutasi Status Karyawan</span>
			</li>
		</ul>
	</div>
	<h1 class="page-title"> Karyawan
		<small>Mutasi Status Karyawan</small>
	</h1>
	<div class="row">

		<div class="col-md-5">
			<div class="portlet box blue-hoki bordered">
				<div class="portlet-title">
					<div class="caption">Filter Karyawan</div>
				</div>
				<div class="portlet-body">
					<form id="form-filter">
						<div class="form-group">
							<label for="">Cabang</label>
							<select class="form-control select2	" id="id_cabang" name="id_cabang" data-placeholder="Pilih Cabang" required autofocus>
								<option value=""></option>
								<?php
								foreach ($cabangs->result() as $cabang) {
									echo '<option value="' . $cabang->parameter_id . '">' . $cabang->description . '</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="">Karyawan</label>
							<select class="form-control select2	" id="nik" name="nik" data-placeholder="Pilih Karyawan" required autofocus>
								<option value=""></option>
							</select>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-7">
			<div class="portlet box blue-hoki bordered">
				<div class="portlet-title">
					<div class="caption">Data Karyawan</div>
				</div>
				<div class="portlet-body">

					<form id="form" action="<?= site_url(); ?>mutasi/action_update_status_karyawan" method="post">
						<div class="form-group">
							<label for="nikx">NIK</label>
							<input type="text" class="form-control" id="nikx" name="nikx" readonly>
						</div>
						<div class="form-group">
							<label for="namax">Nama</label>
							<input type="text" class="form-control" id="namax" name="namax" readonly>
						</div>
						<div class="form-group">
							<label for="cabangx">Cabang</label>
							<input type="text" class="form-control" id="cabangx" name="cabangx" readonly>
						</div>
						<div class="form-group">
							<label for="namastatusx">Status Karyawan Saat ini</label>
							<input type="text" class="form-control" id="statusx" name="statusx" readonly>
						</div>
						<div class="form-group">
							<label for="prevperiodex">Periode Saat ini</label>
							<input type="text" class="form-control" id="prevperiodex" name="prevperiodex" readonly>
						</div>
						<div class="form-group">
							<label for="new_statusx">Status Baru Karyawan</label>
							<select class="form-control" id="new_statusx" name="new_statusx" required>
								<option value=""></option>
								<?php
								foreach ($statuss->result() as $status) {
									echo '<option value="' . $status->parameter_id . '">' . $status->description . '</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="periode_fromx">Periode Baru Awal</label>
							<input type="text" class="form-control date-picker" id="periode_fromx" name="periode_fromx" required>
						</div>
						<div class="form-group">
							<label for="periode_tox">Periode Baru Akhir</label>
							<input type="text" class="form-control date-picker" id="periode_tox" name="periode_tox" required>
						</div>
						<div class="form-group">
							<label for="hak_cutix">Hak Cuti</label>
							<input type="number" class="form-control" id="hak_cutix" name="hak_cutix" min="0" required>
						</div>
						<div class="form-group">
							<label for="hak_ijinx">Hak Ijin</label>
							<input type="number" class="form-control" id="hak_ijinx" name="hak_ijinx" min="0" required>
						</div>
						<div class="form-group">
							<input type="hidden" id="prev_id_status" name="prev_id_status">
							<button type="submit" id="submit" class="btn blue-hoki btn-block sbold uppercase" disabled="true" style="margin-top: 24px">Update</button>
						</div>
					</form>

				</div>
			</div>
		</div>

	</div>
</div>

<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>


<script type="text/javascript">
</script>
<script>
	$(document).ready(function() {
		App.init();
		$(".date-picker").datepicker({
			format: 'dd-mm-yyyy',
			rtl: App.isRTL(),
			autoclose: !0
		});

		$('#id_cabang').on('change', function() {
			let id_cabang = $(this).val();
			getListkaryawan(id_cabang);
		});


		$('#nik').change(function() {
			var nik = $(this).val();
			getInfoKaryawan(nik);
		});

		$('#new_statusx').on('change', function() {
			var status = $(this).val();
			var hak_cuti = $('#hak_cutix');
			var hak_ijin = $('#hak_ijinx');

			/*
			parameter code
			10 = karyawan training
			11 = perpanjang training
			20 = karyawan kontrak 1
			21 = karyawan kontrak 2
			22 = perpanjang kontrak 2
			30 = karyawan tetap
			40 = karyawan magang
			50 = karyawan resign
			*/

			if (status == 10 || status == 11) {
				hak_cuti.val(0);
				hak_ijin.val(0);
			} else if (status == 20) {
				hak_cuti.val(0);
				hak_ijin.val(6);
			} else if (status == 21 || status == 22 || status == 30) {
				hak_cuti.val(12);
				hak_ijin.val(6);
			} else {
				hak_cuti.val(0);
				hak_ijin.val(0);
			}

		});

		$('#status').change(function() {
			var status = $(this).val();

			if (status == 30) {
				$('#update_periode').fadeOut();
			} else {
				$('#update_periode').fadeIn();
			}
		});


		$('#detail').on('show.bs.modal', function(e) {
			var rowid = $(e.relatedTarget).data('id');

			$.ajax({
				type: "POST",
				dataType: "json",
				data: {
					nik: rowid
				},
				url: '<?php echo site_url('mutasi/get_karyawan_by_nik'); ?>',
				success: function(response) {
					$("#niks").val(response.nik);
					$("#karyawan").val(response.fullname);
					$("#branch").val(response.branch);
					$("#position").val(response.position);

				}
			});
		});

	});

	function getListkaryawan(id_cabang) {
		$.ajax({
				url: `<?= site_url(); ?>mutasi/get_list_karyawan/${id_cabang}`,
				method: 'get',
				dataType: 'json',
				beforeSend: function() {
					$.blockUI();
					let nik = $('#nikx');
					let nama = $('#namax');
					let cabang = $('#cabangx');
					let status = $('#statusx');

					// initiate 
					nik.val('');
					nama.val('');
					cabang.val('');
					status.val('');
					$('#submit').attr('disabled', true);
				}
			})
			.done(function(res) {
				$.unblockUI();
				console.log(res);
				let html = '<option value=""></option>';
				$.each(res.data, function(i, k) {
					html += `<option value="${k.nik}">${k.fullname}</option>`;
				});

				$('#nik').html(html);

			});
	}

	function getInfoKaryawan(nik) {
		$.ajax({
				url: '<?php echo site_url('mutasi/get_info_karyawan'); ?>',
				type: "POST",
				dataType: "JSON",
				data: {
					nik: nik
				},
				beforeSend: function() {
					$.blockUI();
					$('#submit').attr('disabled', true);
				}
			})
			.done(function(res) {
				$.unblockUI();
				console.log(res);
				let nik = $('#nikx');
				let nama = $('#namax');
				let cabang = $('#cabangx');
				let status = $('#statusx');
				let prev_periode = $('#prevperiodex');
				let prev_periode_val = '';
				let prev_id_status = $('#prev_id_status');

				// initiate 
				nik.val('');
				nama.val('');
				cabang.val('');
				status.val('');
				prev_periode.val('');

				console.log(res.karyawans.length);

				if (res.karyawans.length > 0) {
					$.each(res.karyawans, function(i, k) {
						let from_date = k.from_date;
						let thru_date = k.thru_date;
						prev_periode_val = `${from_date} s/d ${thru_date}`;
						nik.val(k.nik);
						nama.val(k.fullname);
						cabang.val(k.cabang);
						status.val(k.status);
						prev_periode.val(prev_periode_val);
						prev_id_status.val(k.id_status);
						$('#submit').attr('disabled', false);
					});
				}
			});
	}
</script>