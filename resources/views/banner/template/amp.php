<?php if (false && isset($_GET["amp"]) == 1): ?>
<?php if (false && isset($_GET["debug"]) == 1): ?>
<!-- debug is for test campaign only -->
<amp-fx-flying-carpet height="600px">
	<amp-ad data-block-on-consent width="300" height="600" layout="fixed" type="doubleclick"
		data-slot="/15188745/FS-TestPage-AMP/AMP-FlyingCarpet" data-lazy-fetch="true" data-loading-strategy="1.25"
		rtc-config='{
        "vendors": {
          "t13": {
            "TAG_ID":"b81f3169-ddc9-404b-881a-f3fa262a032b",
            "ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
          }
        }
      }'></amp-ad>
</amp-fx-flying-carpet>
<?php else: ?>
<amp-fx-flying-carpet height="600px">
	<amp-ad data-block-on-consent width="300" height="600" layout="fixed" type="doubleclick"
		data-slot="/15188745,21704504769/FS-Themoscowtimes-AMP/themoscowtimes_AMP_FlyingCarpet" data-lazy-fetch="true"
		data-loading-strategy="1.25" rtc-config='{
     "vendors": {
        "t13": {
          "TAG_ID": "a88ddd84-0cd2-4dc5-98d1-c6660282c206",
          "ACCOUNT_ID": "9a3c6c23-453a-4cba-b419-30b908f39a50"
        },
        "aps": {
          "PUB_ID": "600",
          "PUB_UUID": "16268e26-dabe-4bf4-a28f-b8f4ee192ed3",
          "PARAMS": {
            "amp": "1"
          }
        },
        "criteo": {
          "NETWORK_ID": "4905",
          "ZONE_ID": "1382492",
          "PUBLISHER_SUB_ID": "FS-themoscowtimes-themoscowtimes_AMP_FlyingCarpet"
        },
        "medianet": {
          "CID": "8CU8ZT2C4"
        }
      }
   }'></amp-ad>
</amp-fx-flying-carpet>
<?php endif; ?>
<?php endif; ?>