<html lang="bn">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <meta charset="utf-8" />
    </head>

    <body>

        <div style="border: 1px solid #4F935B; height: 100%;">
            <div>
                <img style="position: relative; " src="{{ asset('server_copy/head0.png') }}" alt="" srcset="">
            </div>
            <div style="margin-top: 5px; " >
                <div style="background-color: #007BFF; padding: 5px; color: white; width: 40px; border-radius: 3px; float: right; margin-right: 10px; ">Home</div>
                <center style="text-align: center; font-size: 18px; font-family: Arial; color: #FF1493;">Select Your Search Category </center>
                <center style="text-align: center; font-size: 13px; font-family: Arial; color: #8AB145;"> <input type="radio" name="" checked="checked" id="" style="color: #0075FF"> Search By NID / Voter No. </center>
                <center style="text-align: center; margin-right: 90px;  "> <input type="radio" name="" > <span style="color: #17A2B8; "> Search By Form No.  </span> </center>
            </div>
            <div style="margin-top: 10px; text-align: center;" >
                <div style="font-size: 13px; font-family: Arial; color: #F00; width: 120px; float: left; margin-left: 160px; " > NID or Voter No* </div>
                <div type="text" name="" style="font-size: 13px; font-family: Arial; border: 1px solid #DCE0E4; color: #6C757D; border-radius: 2px; padding: 4px; width: 120px; text-align: left; float: left; margin-left: 20px;  " > NID </div>
                <div style="background-color: #28A745; font-size: 12px; font-family: Arial; color: white; padding: 6px; width: 40px; border-radius: 3px; float:left; margin-left: 20px;  " > Submit </div>
            </div>
            <div style="margin: 50px; ">
                <div style="width: 120px; float: left; padding: 2px; ">
                    <img width="150px" height="180px" style="" src="<?php echo $services_infos->image_photo; ?>" alt="" srcset="">

                    <div style="font-family: 'Arial ', sans-serif; font-weight: bold; margin-top: 10px; text-align: center; position: absolute; font-size: 13px; " ><?php echo $services_infos->name_en; ?> </div>

                    <img width="110px" height="110px" style="margin: 10px 0 0 15px;  " src="data:image/png;base64,{{  $pdf417_barcode }}" alt="" srcset="">

                </div>
                <div style="width: 490px; float: right; margin: 2px 0 0 30px;  ">
                    <table style="width: 100%; border: 1px solid #EDEFF1 " border="1 #EDEFF1 ">
                        <tr style="background-color: #C5E5EB; " >
                            <td colspan="2" style="font-family: solaimanlipi; font-size: 20px; padding: 5px;  " > জাতীয় পরিচিতি তথ্য</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">জাতীয় পরিচয় পত্র নম্বর</td>
                            <td style="font-size: 16px;"><?php echo $services_infos->nid; ?> </td>
                        </tr>
                        <?php if ($services_infos->pin) { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">পিন নম্বর </td>
                                <td style="font-size: 16px;"><?php echo $services_infos->pin; ?> </td>
                            </tr>
                        <?php }else { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; "> </td>
                                <td style="font-size: 16px;"> </td>
                            </tr>
                        <?php } ?>
                        <?php if ($services_infos->formNo) { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">ফরম নম্বর </td>
                                <td style="font-size: 16px;"><?php echo $services_infos->formNo; ?> </td>
                            </tr>
                        <?php } ?>
                        <?php if ($services_infos->voterNo) { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">ভোটার নম্বর </td>
                                <td style="font-size: 16px;"><?php echo $services_infos->voterNo; ?> </td>
                            </tr>
                        <?php } ?>


                        <tr style="background-color: #C5E5EB; " >
                            <td colspan="2" style="font-family: solaimanlipi; font-size: 20px; padding: 5px;  " > ব্যক্তিগত তথ্য </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">নাম (বাংলা) </td>
                            <td style="font-size: 16px; font-family: solaimanlipi; font-weight: bold; font-size: 16px; "><?php echo $services_infos->nameBangla; ?>  </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">নাম (ইংরেজী) </td>
                            <td style="font-size: 16px;"><?php echo $services_infos->nameEnglish; ?> </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">জন্ম তারিখ </td>
                            <td style="font-size: 16px;">{{ \Illuminate\Support\Carbon::parse($services_infos->dateOfBirth)->format('Y-m-d') }}  </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">পিতার নাম </td>
                            <td style="font-size: 16px; font-family: solaimanlipi;"><?php echo $services_infos->fatherName; ?>  </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">মাতার নাম  </td>
                            <td style="font-size: 16px; font-family: solaimanlipi;"><?php echo $services_infos->motherName; ?>  </td>
                        </tr>
                        <?php if ($services_infos->spouseName) { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">স্বামী / স্ত্রীর নাম  </td>
                                <td style="font-size: 16px; font-family: solaimanlipi;"><?php echo $services_infos->spouseName; ?>  </td>
                            </tr>
                        <?php } ?>


                        <tr style="background-color: #C5E5EB; " >
                            <td colspan="2" style="font-family: solaimanlipi; font-size: 20px; padding: 5px;  " > অন্যান্য তথ্য </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">লিঙ্গ </td>
                            <td style="font-size: 16px;"><?php echo $services_infos->gender; ?>  </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">শিক্ষাগত যোগ্যতা  </td>
                            <?php if ($services_infos->education) { ?>
                                <td style="font-size: 16px; font-family: solaimanlipi"><?php echo $services_infos->education; ?> </td>
                            <?php }else { ?>
                                <td style="font-size: 16px; font-family: solaimanlipi">অন্যান্য </td>
                            <?php }?>
                        </tr>
                        <?php if ($services_infos->bloodGroup) { ?>
                            <tr>
                                <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">রক্তের গ্রুপ  </td>
                                <td style="font-size: 16px; font-family: solaimanlipi; color: red; "><?php echo $services_infos->bloodGroup; ?>  </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style="padding: 5px; font-family: solaimanlipi; font-size: 17px; width: 35%; ">জন্মস্থান  </td>
                            <?php if ($services_infos->birthPlace) { ?>
                                <td style="font-size: 16px; font-family: solaimanlipi"><?php echo $services_infos->birthPlace; ?>  </td>
                            <?php }else { ?>
                                <td style="font-size: 16px; font-family: solaimanlipi"><?php echo $services_infos->voterArea; ?>  </td>
                            <?php }?>
                        </tr>



                        <tr style="background-color: #C5E5EB; " >
                            <td colspan="2" style="font-family: solaimanlipi; font-size: 20px; padding: 5px;  " > বর্তমান ঠিকানা  </td>
                        </tr>
                        <tr>
                            <td colspan="2"  style="padding: 5px; font-family: solaimanlipi; font-size: 17px; "><?php echo $services_infos->presentAddress; ?> </td>
                        </tr>


                        <tr style="background-color: #C5E5EB; " >
                            <td colspan="2" style="font-family: solaimanlipi; font-size: 20px; padding: 5px;  " > স্থায়ী ঠিকানা  </td>
                        </tr>
                        <tr>
                            <td colspan="2"  style="padding: 5px; font-family: solaimanlipi; font-size: 17px; "><?php echo $services_infos->permanentAddress; ?> </td>
                        </tr>


                    </table>
                </div>
            </div>
        </div>

    </body>
</html>


