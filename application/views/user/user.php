<div class="page-content" style="min-height: 860px;">
  <div class="page-bar">
    <ul class="page-breadcrumb">
      <li>
        <a href="<?=site_url('dashboard');?>">Home</a>
        <i class="fa fa-circle"></i>
      </li>
      <li>
        <span>Change Password</span>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tabbable-line boxless tabbable-reversed">
        <div class="tab-content">
          <div class="tab-pane active">
            <div class="portlet box green">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-key"></i>Change Password </div>
                  <div class="tools">
                    <a href="javascript:;" class="collapse"> </a>
                  </div>
                </div>
                <div class="portlet-body form">
                  <?php
                  if($this->session->flashdata('reset') == 'berhasil'){
                    echo '
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Reset Password Berhasil!</strong>
                      </div>
                    ';
                  }elseif($this->session->flashdata('reset') == 'gagal'){
                    echo '
                      <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <strong>Reset Password Gagal!</strong>
                      </div>
                    ';
                  }
                  ?>
                  <?php echo validation_errors(); ?>
                  <?php
                  $attribute = array(
                    'class' => 'form-horizontal'
                  ); 
                  echo form_open('user', $attribute);
                  ?>
                  <div class="form-body">
                    <div class="form-group">
                      <label class="col-md-3 control-label">Full Name</label>
                      <div class="col-md-4">
                        <input type="text" class="form-control input-circle" value="<?php echo $user;?>" name="fullname" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Username</label>
                      <div class="col-md-4">
                        <input type="text" class="form-control input-circle" value="<?php echo $username;?>" name="username" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Old Password</label>
                      <div class="col-md-4">
                        <div class="input-group">
                          <input type="password" class="form-control input-circle-left" name="old_password" autocomplete="off" value="<?=set_value('old_password');?>">
                          <span class="input-group-addon input-circle-right">
                            <i class="fa fa-key"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">New Password</label>
                      <div class="col-md-4">
                        <div class="input-group">
                          <input type="password" class="form-control input-circle-left" name="password" value="<?=set_value('password');?>">
                          <span class="input-group-addon input-circle-right">
                            <i class="fa fa-key"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-3 control-label">Confirm Password</label>
                      <div class="col-md-4">
                        <div class="input-group">
                          <input type="password" class="form-control input-circle-left" name="password2" value="<?=set_value('password2');?>">
                          <span class="input-group-addon input-circle-right">
                            <i class="fa fa-key"></i>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-actions">
                      <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                          <button type="submit" class="btn btn-circle green">Submit</button>
                          <button type="button" class="btn btn-circle grey-salsa btn-outline">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('wraper/footer');?>
