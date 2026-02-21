



<html lang="bn">
<head>
    <meta charset="utf-8">
    <style>
      body { font-family: solaimanlipi, sans-serif; }
    </style>
    <link rel="stylesheet" href="{{ asset('fonts/font.css') }}">
</head>

<body>
    <div class="" style=" width: 332px; margin: 0px 6.38px 0 20px; float: left;">
        <div class="" style="margin: 0; padding: 0; border: 1.5px solid black; width: 320px;  height: 205px;">
            <img src="{{ asset('card_img/bd_logo_0.jpg') }}" width="37px" height="35px" alt=""
                style="margin: 8px 0 0 5px; float: left;">
            <p style=" margin: 3px 0 0 0px; font-size: 20px; text-align: center; font-family: 'SolaimanLipi', sans-serif;">
                গণপ্রজাতন্ত্রী বাংলাদেশ সরকার
            </p>
            <p style="font-size: 11px; text-align: center;  margin: -19px 0 0 35px; font-family: Arial; color: #007700;">
                Government of the People's Republic of Bangladesh
            </p>
            <p style="font-size: 10px; text-align: center; margin: 2px 0 0 20px; font-family: Arial, Helvetica, sans-serif; color: #FF0000;">
                National ID Card
                <span style="color: #000000; font-family: 'SolaimanLipi', sans-serif;">
                    / জাতীয় পরিচয় পত্র
                </span>
            </p>



            <div style="border: 0.7px solid #000000 ; margin: 7px 0 0 0; padding: 0;"></div>
            <div class="" style=" width:74px; margin: 0; padding: 0; float: left; ">
                <img id="image_pic_person" width="70px" height="78px" src="{{ asset($services_infos->image_photo) }}" alt="" style="position: absolute; margin: 2px 0px 2px 3px; padding: 0; "><br>
                <img height="28px" width="70px" id="images_sign_pisc" src="{{ asset($services_infos->image_sign) }}" alt="" style="position: absolute; margin: 3px 2px 2px 2px; padding: 0;">
            </div>

            <div style=" width: 245px; float: left; margin: 2px 0 0 0; background:url('{{ asset('card_img/back.jpg') }}'); background-repeat: no-repeat; background-size: 680px 380px; background-position: 5px 0px;"
                class="">
                <div style="font-family: SolaimanLipi; font-size: 13px; float: left; margin: 1.5px 2px 0 8px">
                    নাম<span style="font-family: Arial;">:</span>
                </div>
                <div style="font-family: SolaimanLipi; font-size: 14px; margin: -18px 0 0 49px" class="name_SolaimanLipi">
                    <b><?php echo $services_infos->nameBangla; ?></b>
                </div>
                <div style="font-family: Arial; font-size: 10px;float: left; margin: 6px 0 0 8px ;">
                    Name:
                </div>
                <div style="font-family: arial, sans-serif;  font-size: 12px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; float: left; margin: -16px 0 0 49px;">
                    <?php echo $services_infos->nameEnglish; ?>
                </div>
                <div style="font-family: SolaimanLipi; font-size: 12px; float: left; margin: 2px 0 0 8px ;">
                    পিতা:
                </div>
                <div style="font-family: SolaimanLipi; font-size: 12px; float: left; margin: -16px 0 0 49px;">
                    <?php echo $services_infos->fatherName; ?>
                </div>
                <div style="font-family: SolaimanLipi; font-size: 12px; float: left;  margin: 2px 0 0 8px ;">
                    মাতা:
                </div>
                <div style="font-family: SolaimanLipi; font-size: 12px; float: left; margin:  -16px 0 0 49px;">
                    <?php echo $services_infos->motherName; ?>
                </div>
                <div style="margin-top: 2px;">
                    <div style="font-family: Arial; font-size: 12px; float: left; margin:  1.5px 0 0 8px ;">Date of
                        Birth:
                        <span style="color: #FF0000; "> <?php echo date('d M Y', strtotime($services_infos->dateOfBirth)); ?></span>
                    </div>
                    <div style="font-family: Arial; font-size: 13px; float: left; margin: 0px 0 0 8px ;">ID NO:
                        <span style="color: #FF0000; font-weight: bold; font-size: 12px; "><?php echo $services_infos->nid; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="" style="margin: 0px 6.38px 0 0px; float: left; width: 322px;">
        <div class="" style="margin: 0; padding: 0; border: 1.5px solid black; width: 320px;  height: 205px;">
            <div style="font-family: SolaimanLipi; font-size: 9.5px; margin: 3px 0 4px 7px;">
                এই কার্ডটি গণপ্রজাতন্ত্রী বাংলাদেশ সরকারের সম্পত্তি। কার্ডটি ব্যবহারকারী ব্যতীত অন্য <br> কোথাও পাওয়া
                গেলে নিকটস্থ পোস্ট অফিসে জমা দেবার জন্য অনুরোধ করা হলো।
            </div>
            <div style="border-top: 1.5px solid #000000 ; margin: 0px 0 0 0; padding: 0;"></div>
            <div class=""
                style=" width: 30px; font-family: SolaimanLipi; font-size: 9.5px; margin: 1px 0px 6px 7px; float: left;">
                ঠিকানা:
            </div>
            <div style="font-family: SolaimanLipi; font-size: 9.5px; margin: 1px 3px 18px 2px; height: 30px">
                <?php echo en_to_bn_number($services_infos->address); ?>
            </div>

            <div class=""
                style=" width: 130px; font-family: SolaimanLipi; font-size: 9.5px; margin: 5px 0px 0px 4px; float: left; ">
                রক্তের গ্রুপ /
                <span style="font-family: Arial; ">
                    Blood Group:
                    <span style="color: #FF0000; font-weight: bold;">
                        <?php echo $services_infos->bloodGroup; ?>
                    </span>
                </span>
            </div>
            <div style="font-family: SolaimanLipi; margin-left: 5px; font-size: 9px; width: 100px; float: left;">
                জন্মস্থান: <?php echo $services_infos->birthPlace; ?>
            </div>
            <div
                style="font-family: SolaimanLipi; font-size: 8.8px; color: white; background-color: #000000; width: 35px; margin: 0; float: right; padding: 0 1px 1px 0; ">
                মূদ্রণ: ০১</div>
            <div style="border-bottom: #000000 1.5px solid; margin: 13px 0 0 0 "></div>
            <div>
                <img style="margin: 1px 0 0 20px;" src="{{ asset('card_img/ecsign.png') }}" width="70px" height="32px" alt=""><br>
                <div style="font-family: SolaimanLipi; width: 160px ; font-size: 11px; margin: -5px 0 0px 7px; float: left; "
                    class="">
                    প্রদানকারী কর্তৃপক্ষের স্বাক্ষর
                </div>
                <div style="font-family: SolaimanLipi; font-size: 10px; margin: 1px 0px 0px 25px; float: right;"
                    class="">
                    প্রদানের তারিখ:
                    <span style="font-family: SolaimanLipi;"><?php echo en_to_bn_number(date("d/m/Y", strtotime($services_infos->issueDate))) ; ?></span>
                </div>
            </div>
            <center style="margin: 3px 0 0 3px; position: absolute; float: left;">
                <img style="margin: 0; padding: 0;" src="{{ $pdf417_barcode }}" width="315px" height="41" alt="">
            </center>
        </div>
    </div>


</body>

</html>



<!-- top 6 0 0 3.5 -->
