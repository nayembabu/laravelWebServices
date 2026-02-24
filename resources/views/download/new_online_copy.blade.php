<html lang="bn">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "v1_" . $services_infos->nid; ?></title>
  <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('server_copy/server_v1.css') }}">
</head>
<body>

  <div class="container" >
    <div class="header">
      <div class="header_top">
        <img  src="{{ asset('server_copy/img/logo-server-copy.svg') }}" alt="" class="logo">
        <p class="text_one text">বাংলাদেশ নির্বাচন কমিশন</p>
        <p class="text_two text">নির্বাচন কমিশন সচিবালয়</p>
        <p class="text_three text">জাতীয় পরিচয় নিবন্ধন অনুবিভাগ</p>
      </div>
        <div class="user_photo"><img src="<?php echo $services_infos->image_photo; ?>" alt="" id="user_img">
      </div>
    </div>
    <div class="sub_container">
      <div class="section">
        <div class="section-title">জাতীয় পরিচিতি তথ্য</div>
        <div class="section-content">
          <table>
                <tr><td><strong>জাতীয় পরিচয় পত্র নম্বর</strong></td><td>{{ $services_infos->nid }}</td></tr>
                <tr><td><strong>পিন নম্বর</strong></td><td>{{ $services_infos->pin }}</td></tr>
                <tr><td><strong>ভোটার নম্বর</strong></td><td>{{ $services_infos->voterNo }}</td></tr>
                <tr><td><strong>ভোটার এলাকা</strong></td><td>{{ $services_infos->voterArea }}</td></tr>
                <tr><td><strong>ভোটার সিরিয়াল নম্বর</strong></td><td>{{ $services_infos->sl_no }}</td></tr>
          </table>
        </div>
      </div>

      <div class="section">
        <div class="section-title">ব্যক্তিগত তথ্য</div>
        <div class="section-content">
          <table>
                <tr><td><strong>নাম (বাংলা)</strong></td><td>{{ $services_infos->nameBangla }}</td></tr>
                <tr><td><strong>নাম (ইংরেজি)</strong></td><td>{{ $services_infos->nameEnglish }}</td></tr>
                <tr><td><strong>জন্ম তারিখ</strong></td><td>{{ \Illuminate\Support\Carbon::parse($services_infos->dateOfBirth)->format('Y-m-d') }}</td></tr>
                <tr><td><strong>পিতার নাম</strong></td><td>{{ $services_infos->fatherName }}</td></tr>
                <tr><td><strong>মাতার নাম</strong></td><td>{{ $services_infos->motherName }}</td></tr>
                <tr><td><strong>স্বামী/স্ত্রীর নাম</strong></td><td>{{ $services_infos->spouseName }}</td></tr>
          </table>
        </div>
      </div>

      <div class="section">
        <div class="section-title">অন্যান্য তথ্য</div>
        <div class="section-content">
          <table>
            <tr><td><strong>রক্তের গ্রুপ</strong></td><td>{{ $services_infos->bloodGroup }}</td></tr>
            <tr><td><strong>পেশা</strong></td><td>{{ $services_infos->occupation }}</td></tr>
            <tr><td><strong>শিক্ষাগত যোগ্যতা</strong></td><td>{{ $services_infos->education }}</td></tr>
            <tr><td><strong>লিঙ্গ</strong></td><td>{{ $services_infos->gender }}</td></tr>
            <tr><td><strong>ধর্ম</strong></td><td>{{ $services_infos->religion }}</td></tr>
            <tr><td><strong>জন্মস্থান</strong></td><td>{{ $services_infos->birthPlace }}</td></tr>
          </table>
        </div>
      </div>

      <div class="section">
        <div class="section-title">বর্তমান ঠিকানা</div>
        <div class="section-content">
          <table>
            <colgroup>
              <col>
            </colgroup>
            <?php
                echo "<tr><td>{$services_infos->presentAddress}</td></tr>";
            ?>
          </table>
        </div>
      </div>

      <div class="section">
        <div class="section-title">স্থায়ী ঠিকানা</div>
        <div class="section-content">
          <table>
            <colgroup>
              <col>
            </colgroup>
            <?php
                echo "<tr><td>{$services_infos->permanentAddress}</td></tr>";
            ?>
          </table>
        </div>
      </div>

      <div class="footer_text">
        <p style="text-align: center; color: red; margin: 5px 0 5px 0; padding: 0;" >উপরে প্রদর্শিত তথ্যসমূহ জাতীয় পরিচয়পত্র সংশ্লিষ্ট, ভোটার
          তালিকার
          সাথে সরাসরি সম্পর্কযুক্ত নয়।</p>
        <p id="footer_english" style="margin: 0; padding: 0;">This is Software Generated Report From Bangladesh Election Commission, Signature
          &
          Seal Aren't Required.</p>
      </div>
    </div>
  </div>
</body>
<script src="{{ asset('server_copy/server_vs1.js') }}"></script>
<script disable-devtool-auto="" src="https://cdn.jsdelivr.net/npm/disable-devtool@latest"></script>
<script src="assets/js/disabled.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('contextmenu', function (e) {
      e.preventDefault();
    });

    document.onkeydown = function (e) {
      if (e.ctrlKey && (e.key === 'u' || e.key === 'c' || e.key === 's')) {
        e.preventDefault();
      }
    };


  });
  window.onload = function () { setTimeout(wp, 500); }; function wp() { window.print(); }
</script>
</html>
