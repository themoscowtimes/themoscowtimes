<?php
  $covid19 = fetch::covid19();
?>

<section class="covid19-ticker flex flex-column">

  <?php if ($covid19['httpStatus'] !== 200 || !is_null($covid19['error'])): ?>
  <div class="block">
    <span class="label">Data Not Available</span>
  </div>
  <?php else: ?>
  <!-- potential for loop -->
  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['cases']) ?? ''); ?></span>
    <span class="label">Total Confirmed Cases</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['active']) ?? ''); ?></span>
    <span class="label">Total Active Cases</span>
  </div>

  <div class="today block">
    <span class="digit"><?php view::text(number_format($covid19['data']['todayCases']) ?? ''); ?></span>
    <span class="label">Today's Cases</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['recovered']) ?? ''); ?></span>
    <span class="label">Total Recovered</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['critical']) ?? ''); ?></span>
    <span class="label">Total Critical</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['todayDeaths']) ?? ''); ?></span>
    <span class="label">Today's Deaths</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['deaths']) ?? ''); ?></span>
    <span class="label">Total Deaths</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['casesPerOneMillion']) ?? ''); ?></span>
    <span class="label">Cases per Million</span>
  </div>

  <div class="block">
    <span class="digit"><?php view::text(number_format($covid19['data']['deathsPerOneMillion']) ?? ''); ?></span>
    <span class="label">Deaths per Million</span>
  </div>

  <div class="source block"><a href="https://github.com/disease-sh/api/blob/master/README.md#sources">Data Sources</a></div>
  <div class="source block" style="margin-top: -10px;">API: <a href="https://disease.sh/">Open Disease Data API</a>
  </div>
  <?php endif; ?>
</section>