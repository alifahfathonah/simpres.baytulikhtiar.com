<div class="page-content" style="min-height: 860px;">
	<div class="theme-panel hidden-xs hidden-sm">
	</div>
	<div class="page-bar">
		<ul class="page-breadcrumb">
			<li>
				<a href="<?=site_url('dashboard');?>">Home</a>
				<i class="fa fa-circle"></i>
			</li>
			<li>
				<span>Laporan</span>
			</li>
		</ul>
	</div>
	<h1 class="page-title"> Laporan
		<small>List Karyawan</small>
	</h1>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet light bordered">
				<div class="portlet-title">


					<form action="javascript:;">
						<div class="form-group col-md-4">
							<label for="single-prepend-text" class="control-label">Kantor</label>
							<div class="input-group select2-bootstrap-prepend">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" data-select2-open="single-prepend-text">
										<span class="glyphicon glyphicon-search"></span>
									</button>
								</span>
								<select class="form-control select2" name="branch_code" id="branch_code">
									<option></option>
									<?php foreach($get_branch as $values){?>
										<option value="<?php echo $values->parameter_id;?>"><?php echo $values->description;?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<!--<button id="pdf" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">PDF</button>-->
							<button id="excel" class="btn red-mint btn-outline sbold uppercase" style="margin-top: 24px">Excel</button>
						</div>
					</form>


				</div>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo base_url();?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>

<script type="text/javascript">
	jQuery(document).ready(function(){
		App.init();
		$(function(){

			$('#excel').click(function(){
				var branch_code = $('#branch_code').val();
				var site = '<?php echo site_url('laporan/action_laporan_list_karyawan_excel'); ?>';
				var conf = true;

				if(branch_code == ''){
					alert('Kantor belum diisi');
					var conf = false;
				}

				if(conf == true){
					window.open(site+'/'+branch_code);
				}
			});

			$('#pdf').click(function(){
				var branch_code = $('#branch_code').val();
				var site = '<?php echo site_url('laporan/action_laporan_list_karyawan_pdf'); ?>';
				var conf = true;

				if(branch_code == ''){
					alert('Kantor belum diisi');
					var conf = false;
				}

				if(conf == true){
					window.open(site+'/'+branch_code);
				}
			});

		});
	})
</script>

