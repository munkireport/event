<?php 

$this->view('listings/default',
[
  "i18n_title" => 'event.event_plural',
  "js" => "var eventMessageCombined = function(colNumber, row){
      var cell1 = $('td:eq('+colNumber+')', row);
      var cell2 = $('td:eq('+(colNumber+1)+')', row);
      cell1.text(i18n.t(cell1.text(), JSON.parse(cell2.text())));
      cell2.hide();
  }",
  "table" => [
    [
      "column" => "machine.computer_name",
      "i18n_header" => "listing.computername",
      "formatter" => "clientDetail",
    ],
    [
      "column" => "reportdata.serial_number",
      "i18n_header" => "serial",
    ],
    [
      "column" => "reportdata.long_username",
      "i18n_header" => "username",
    ],
    [
      "column" => "event.type",
      "i18n_header" => "type",
    ],
    [
      "column" => "event.module",
      "i18n_header" => "module",
    ],
    [
      "column" => "event.msg",
      "i18n_header" => "message",
      "formatter" => "eventMessageCombined",
    ],
    [
      "column" => "event.data",
      'hide' => true,
    ],
    [
      "column" => "event.timestamp",
      "i18n_header" => "last_seen",
      "formatter" => "timestampToMoment",
    ],
  ]
]);
