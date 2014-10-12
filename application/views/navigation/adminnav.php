<ul>
   <li class='active'><a href="<?php echo site_url('dashboard/' . $this->session->userdata('jms_job_type')); ?>"><span>Dashboard</span></a></li>
   <li class='has-sub'><a href='#'><span>JO</span></a>
      <ul>
         <li><a href="#"><span>Create New JO</span></a></li>
         <li class='last'><a id="link-edit-jo" href="#"><span>Edit JO</span></a></li>
      </ul>
   </li>
   <script>
   
   </script>
   <li class='has-sub'><a href='#'><span>Division</span></a>
      <ul>
         <li><a href="<?php echo site_url('division/index'); ?>"><span>View Division</span></a></li>
         <li><a href="<?php echo site_url('division/index/add'); ?>"><span>Create Division</span></a></li>
         <li><a href="<?php echo site_url('division/activity'); ?>"><span>View Division Activity</span></a></li>
         <li class='last'><a href="<?php echo site_url('division/activity/add'); ?>"><span>Create Division Activity</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>User</span></a>
      <ul>
         <li><a href="<?php echo site_url('user/index'); ?>"><span>View User</span></a></li>
         <li class='last'><a href="<?php echo site_url('user/index/add'); ?>"><span>Create User</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Product</span></a>
      <ul>
         <li><a href="<?php echo site_url('product/index'); ?>"><span>View Product</span></a></li>
         <li class='last'><a href="<?php echo site_url('product/index/add'); ?>"><span>Create Product</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Customer</span></a>
      <ul>
         <li><a href="<?php echo site_url('customer/index'); ?>"><span>View Customer</span></a></li>
         <li class='last'><a href="<?php echo site_url('customer/index/add'); ?>"><span>Create Customer</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Email</span></a>
      <ul>
         <li><a href="<?php echo site_url('email/index'); ?>"><span>View Notification</span></a></li>
         <li class='last'><a href="<?php echo site_url('email/index/add'); ?>"><span>Add Email</span></a></li>
      </ul>
   </li>
   <li class='has-sub'><a href='#'><span>Report</span></a>
      <ul>
         <li><a href="<?php echo site_url('report/cummulative'); ?>"><span>Cummulative Chart</span></a></li>
         <li><a href="<?php echo site_url('report/incremental/week/' . date('Y')); ?>"><span>Incremental Chart</span></a></li>
         <li class='last'><a href="<?php echo site_url('report/summary/9000/' . date('Y')); ?>"><span>Summary Chart</span></a></li>
      </ul>
   </li>
</ul>