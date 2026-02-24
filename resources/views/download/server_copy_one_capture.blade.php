<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta charset="utf-8" />
	<link rel="stylesheet" href="inc/assets/server_copy_design/add_one_css.css">
</head>

<body style="margin: 0;">

<div id="p1" style="overflow: hidden; position: relative; background-color: white; width: 1287px; height: 1821px;">

<!-- Begin shared CSS values -->

<!-- End shared CSS values -->

<image  style="position: absolute; top: 480px; left: 216px; border-radius: 20px; z-index:1;" width="207" height="240" src="inc/assets/server_copy_design/1/img/2.jpg" />
<image style="position: absolute; top: 761px; left: 248px; z-index:1;" width="137" height="137" src="inc/assets/server_copy_design/1/img/3.jpg" />


<!-- Begin page background -->
<div id="pg1Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg1" style="-webkit-user-select: none;"><object width="1287" height="1821" data="inc/assets/server_copy_design/1/1.svg" type="image/svg+xml" id="pdf1" style="width:1287px; height:1821px; -moz-transform:scale(1); z-index: 0;"></object></div>
<!-- End page background -->




<!-- Begin text definitions (Positioned/styled in CSS) -->
<span id="t5_1" class="t s2">National Identity Registration Wing (NIDW) </span>
<span id="t6_1" class="t s3">Select Your Search Category </span>
<span id="t7_1" class="t s4">Search By NID / Voter No. </span>
<span id="t8_1" class="t s5">Search By Form No. </span>
<span id="t9_1" class="t s6">NID or Voter No* </span><span id="ta_1" class="t s7">NID </span><span id="tb_1" class="t s8">Submit </span>
<span id="tc_1" class="t s8">Home </span>
<span id="td_1" class="t s9">উপরে </span><span id="te_1" class="t s9">প্রদর্শিত </span><span id="tf_1" class="t s9">তথ্যসমূহ </span><span id="tg_1" class="t s9">জাতীয় </span><span id="th_1" class="t s9">পরিচয়পত্র </span><span id="ti_1" class="t s9">সংশ্লিষ্ট, </span><span id="tk_1" class="t s9">ভোটার </span><span id="tl_1" class="t s9">তালিকার </span><span id="tm_1" class="t s9">সাথে </span><span id="tn_1" class="t s9">সরাসরি </span><span id="to_1" class="t s9">সম্পর্কযুক্ত  </span><span id="tp_1" class="t s9">নয়। </span>
<span id="tq_1" class="t sb">This is Software Generated Report From Bangladesh Election Commission, Signature &amp; Seal Aren't Required. </span>
<span id="tr_1" class="t sc"><?php echo $service_info->name_en; ?> </span>
<span id="ts_1" class="t sd">জাতীয় </span><span id="tt_1" class="t sd">পরিচিতি </span><span id="tu_1" class="t sd">তথ্য </span>
<span id="tv_1" class="t se">জাতীয় </span><span id="tw_1" class="t se">পরিচয় </span><span id="tx_1" class="t se">পত্র </span><span id="ty_1" class="t se">নম্বর </span>
<span id="tz_1" class="t sb"><?php echo $service_info->nid_no_s; ?> </span>
	<span id="t10_1" class="t se"></span><span id="t11_1" class="t se">পিন </span><span id="t12_1" class="t se">নম্বর </span>
<?php if ($service_info->pin_no) { ?>
	<span id="t13_1" class="t sb"><?php echo $service_info->pin_no; ?> </span>
<?php }else { ?>
<span id="t13_1" class="t sb">0 </span>
<?php } ?> ?>
<span id="t14_1" class="t se">ফরম </span><span id="t15_1" class="t se">নম্বর </span>
<?php if ($service_info->form_no) { ?>
<span id="t16_1" class="t sb"><?php echo $service_info->form_no; ?> </span>
<?php }else { ?>
<span id="t16_1" class="t sb">0 </span>
<?php } ?> ?>
<span id="t17_1" class="t se">ভোটার </span><span id="t18_1" class="t se">নম্বর </span>
<?php if ($service_info->voter_no) { ?>
<span id="t19_1" class="t sb"><?php echo $service_info->voter_no; ?> </span>
<?php }else { ?>
<span id="t19_1" class="t sb">0 </span>
<?php } ?>
<span id="t1a_1" class="t sd">ব্যক্তিগত </span><span id="t1b_1" class="t sd">তথ্য </span>
<span id="t1c_1" class="t se">নাম </span><span id="t1d_1" class="t sf">(</span><span id="t1e_1" class="t se">বাংলা</span><span id="t1f_1" class="t sf">) </span><span id="t1g_1" class="t sg"><?php echo $service_info->name_bn; ?> </span>
<span id="t1j_1" class="t se">নাম </span><span id="t1k_1" class="t sf">(</span><span id="t1l_1" class="t se">ইংরেজী</span><span id="t1m_1" class="t sf">) </span>
<span id="t1n_1" class="t sb"><?php echo $service_info->name_en; ?> </span>
<span id="t1o_1" class="t se">জন্ম </span><span id="t1p_1" class="t se">তারিখ </span>
<span id="t1q_1" class="t sb"><?php echo date('Y-m-d', strtotime($service_info->biirth_dates)); ?> </span>
<span id="t1r_1" class="t se"></span><span id="t1s_1" class="t se">পিতার </span><span id="t1t_1" class="t se">নাম </span><span id="t1u_1" class="t se"><?php echo $service_info->father_name; ?> </span>
<span id="t1x_1" class="t se">মাতার </span><span id="t1y_1" class="t se">নাম </span><span id="t1z_1" class="t se"><?php echo $service_info->mother_name; ?> </span>
<span id="t20_1" class="t se">স্বামী </span><span id="t21_1" class="t sf">/ </span><span id="t22_1" class="t se">স্ত্রীর </span><span id="t23_1" class="t se">নাম </span>

<?php if ($service_info->spouse_name_bn) { ?>
<span id="t24_1" class="t se"><?php echo $service_info->spouse_name_bn; ?> </span>
<?php }else { ?>
<span id="t19_1" class="t sb"> </span>
<?php } ?>




<span id="t25_1" class="t sd">অন্যান্য </span><span id="t26_1" class="t sd">তথ্য </span>
<span id="t27_1" class="t se">লিঙ্গ </span>
<span id="t29_1" class="t sb"><?php echo $service_info->user_gender_s; ?> </span>
<span id="t2a_1" class="t se">শিক্ষাগত </span><span id="t2c_1" class="t se">যোগ্যতা </span>

<?php if ($service_info->education_qualify) { ?>
<span id="t2d_1" class="t se"><?php echo $service_info->education_qualify; ?> </span>
<?php }else { ?>
<span id="t19_1" class="t sb">অন্যান্য </span>
<?php } ?>

<span id="t2e_1" class="t se">জন্মস্থান </span>

<?php if ($service_info->birth_place) { ?>
<span id="t2f_1" class="t se"><?php echo $service_info->birth_place; ?> </span>
<?php }else { ?>
<span id="t2f_1" class="t se"><?php echo $service_info->parmament_dist; ?> </span>
<?php } ?>

<span id="t2g_1" class="t sd">বর্তমান </span><span id="t2h_1" class="t sd">ঠিকানা </span>
<span id="t2i_1" class="t se" >
<?php echo $service_info->present_address_copy; ?> </span>
<span id="t3r_1" class="t sd">স্থায়ী </span><span id="t3s_1" class="t sd">ঠিকানা </span>
<span id="t3t_1" class="t se" style="word-wrap: break-word;"><?php echo $service_info->parmanent_address_copy; ?> </span></div>
<!-- End text definitions -->


</div>
</body>
</html>
