<!DOCTYPE html>
<html>
<head>
<title><?php echo $title?></title>
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/default_layout.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/navigation_styles.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/css/jquery-ui-1.10.3.custom.css');?>" />
<link rel="stylesheet" href="<?php echo base_url('/fancybox/jquery.fancybox.css');?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url('/fancybox/jquery.fancybox-thumbs.css');?>" type="text/css" media="screen" />

<script type='text/javascript' src="<?php echo base_url('/js/jquery-1.9.1.js');?>"></script>
<script type='text/javascript' src='<?php echo base_url("/js/jquery.min.js");?>'></script>
<script type='text/javascript' src="<?php echo base_url('/js/jquery-ui.js');?>"></script>
<script type='text/javascript' src='<?php echo base_url("/js/jquery.blockUI.js");?>'></script>
<script type='text/javascript' src='<?php echo base_url("/js/jquery.knob.js");?>'></script>
<script type='text/javascript' src='<?php echo base_url("/js/jquery.form.js");?>'></script>
<script type="text/javascript" src='<?php echo base_url("/fancybox/jquery.fancybox.js"); ?>'></script>
<script type="text/javascript" src='<?php echo base_url("/fancybox/jquery.fancybox-thumb.js"); ?>'></script>
        <script>
            $(function($) {

                $(".knob").knob({
                    change : function (value) {
                        //console.log("change : " + value);
                    },
                    release : function (value) {
                        //console.log(this.$.attr('value'));
                        console.log("release : " + value);
                    },
                    cancel : function () {
                        console.log("cancel : ", this);
                    },
                    draw : function () {

                        // "tron" case
                        if(this.$.data('skin') == 'tron') {

                            var a = this.angle(this.cv)  // Angle
                                , sa = this.startAngle          // Previous start angle
                                , sat = this.startAngle         // Start angle
                                , ea                            // Previous end angle
                                , eat = sat + a                 // End angle
                                , r = 1;

                            this.g.lineWidth = this.lineWidth;

                            this.o.cursor
                                && (sat = eat - 0.3)
                                && (eat = eat + 0.3);

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.v);
                                this.o.cursor
                                    && (sa = ea - 0.3)
                                    && (ea = ea + 0.3);
                                this.g.beginPath();
                                this.g.strokeStyle = this.pColor;
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                                this.g.stroke();
                            }

                            this.g.beginPath();
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                            this.g.stroke();

                            this.g.lineWidth = 2;
                            this.g.beginPath();
                            this.g.strokeStyle = this.o.fgColor;
                            this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                            this.g.stroke();

                            return false;
                        }
                    }
                });

                // Example of infinite knob, iPod click wheel
                var v, up=0,down=0,i=0
                    ,$idir = $("div.idir")
                    ,$ival = $("div.ival")
                    ,incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); }
                    ,decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
                $("input.infinite").knob(
                                    {
                                    min : 0
                                    , max : 20
                                    , stopper : false
                                    , change : function () {
                                                    if(v > this.cv){
                                                        if(up){
                                                            decr();
                                                            up=0;
                                                        }else{up=1;down=0;}
                                                    } else {
                                                        if(v < this.cv){
                                                            if(down){
                                                                incr();
                                                                down=0;
                                                            }else{down=1;up=0;}
                                                        }
                                                    }
                                                    v = this.cv;
                                                }
                                    });
            });
        </script>
<style>
  body { font-size: 62.5%; }
  label, input { display:block; }
  input.text { margin-bottom:12px; width:95%; padding: .4em; }
  fieldset { padding:0; border:0; margin-top:25px; }
  h1 { font-size: 1.2em; margin: .6em 0; }
  div#users-contain { width: 350px; margin: 20px 0; }
  div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
  div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
  .ui-dialog .ui-state-error { padding: .3em; }
  .validateTips { border: 1px solid transparent; padding: 0.3em; }
  div.demo{text-align: center; width: 280px; float: left}
  div.demo > p{font-size: 20px}
  #link-ct{float:left;}
  #link-p{float:left; }
  #link-el{float:left;}
  #link img:hover
  {
  	opacity: 0.5;
  }
</style>

</head>
<body>
	<div id="wrapper">
        <div id="header">
        	<div id="logo" style="float: left;">
        		<span><a href='/'><img src=<?php echo base_url("/images/nobi-logo.png");?> style='margin-top:10px;margin-left:20px;width: 120px;' /></a></span>	
        	</div>
        	<div style="float: left;padding-top:18px;">
        		<h2>JMS Web Application</h2>
        	</div>
        	<div id="link" style="float:right; margin-top: 10px;margin-right: 20px;">
        		<div id="link-ct">
        			<span><a href="<?php echo base_url('dashboard/cable_tray');?>"><img src=<?php echo base_url("/images/ct-link.png");?> style='margin-top:10px;margin-left:10px;width: 50px;' /></a></span>
        		</div>
        		<div id="link-p">
        			<span><a href="<?php echo base_url('dashboard/panel');?>"><img src=<?php echo base_url("/images/p-link.png");?> style='margin-top:10px;margin-left:10px;width: 50px;' /></a></span>
        		</div>
        		<div id="link-el">
        			<span><a href="<?php echo base_url('dashboard/electrical');?>"><img src=<?php echo base_url("/images/el-link.png");?> style='margin-top:10px;margin-left:10px;width: 50px;' /></a></span>
        		</div>
        	</div>
        </div>
        <div id="navigation">
        	<div id='cssmenu'>
            	<?php echo $navmenu;?>
            	<ul style="padding-top:12px;text-align:right;padding-right:50px;color: white;text-shadow: 0px 1px 1px rgba(0, 0, 0, 0.36);font-size: 12px;">
            		<?php 
            		if($this->session->userdata('jms_username'))
            		{
            			echo 'Welcome, ' . $this->session->userdata('jms_fullname');
            		}
            		else
            		{
            			echo 'Your are not login. Login ';
            			?>
            			<a id="login-link" href="#" style="color: white;">Here</a>
            			<?php 
            		}
            		?>
            	</ul>
            </div>
        </div>
        <div id="contentliquid" class="fade" style="padding-top: 10px;"><div id="content">
           <?php 
			if(isset($output->css_files))
			{
				foreach($output->css_files as $file): ?>
    			<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
			<?php endforeach; 
			}?>
			<?php 
			if(isset($output->js_files))
			{
				foreach($output->js_files as $file): ?>
				<script src="<?php echo $file; ?>"></script>
			<?php endforeach; 
			}?>
			<?php if(isset($body)){echo $body;} ?>
		</div></div>
		<div style="clear:both;margin-left:auto;margin-right:auto;width:70%;text-align:center;padding:10px 0 10px 0;">
    		<a href="<?php echo site_url('about/index');?>">About</a>
    		<?php 
    		if($this->session->userdata('jms_username'))
    		{    		
    		?>
    		<a>|</a> 
    		<a href="<?php echo site_url('login/log_me_out')?>">
    			logout
    		</a>
    		<?php } ?>
    	</div>
        <div id="footer" style="text-align: center;">
            <div style="padding-top: 15px;"><a>P.T Nobi Putra Angkasa</a> &copy 2013</div>
        </div>
  </div>
	
 <?php
 
 if(!$this->session->userdata('jms_username'))
 {?>
 <script>
 $(document).ready(function() {
   var name = $( "#username" ),
     password = $( "#password" ),
     allFields = $( [] ).add( name ).add( password );
	
   $( "#dialog-form" ).dialog({
     autoOpen: false,
     height: 250,
     width: 350,
     modal: true,
     buttons: {
       "Login": function() {
       	var data_post = {};
   		data_post['username'] = $("#username").val();
   		data_post['password'] = $("#password").val();
       	$.ajax({
				url: "<?php echo site_url('login/log_me_in');?>",
				type: "POST",
				data: data_post,
				success: function(output){
					if(output == "failed")
						{
							$("#login-result").html(output);
						}
						else
						{
							window.location.href="<?php echo site_url();?>" + "dashboard/" + output;
						}
					}
           	});
       },
       Cancel: function() {
         $( this ).dialog( "close" );
       }
     },
     close: function() {
       allFields.val( "" ).removeClass( "ui-state-error" );
     }
   });

   $( "#login-link" ).click(function() {
       $( "#dialog-form" ).dialog( "open" );
     });
 });
 </script>  
 <div id="dialog-form" title="User Login" style="z-index: 500;">
 <p class="validateTips">All form fields are required.</p>
 <div id="login-result" style="color: red;"></div>
 <fieldset>
   <label for="name">Username</label>
   <input type="text" name="username" id="username" class="text ui-widget-content ui-corner-all" />
   <label for="password">Password</label>
   <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
 </fieldset>
</div>
<?php 
 }
?>
</body>
</html>