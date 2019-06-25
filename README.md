Event module
==============

Provides client messages which can come from report_broken_client or other scripts

The table provides the following information:

* type (string) Use one of 'danger', 'warning', 'info' or 'success'
* module (string) The name of the reporting module
* msg (string) a localized message identifier (will be rendered by i18n)
* data (string) optional data to add to the message
* timestamp (int) UNIX timestamp

Remarks
---
* Every module can only store one message. So you should only store the most relevant one.
* A newer message will overwrite the previous message.
* The content of the event table will be used to render events in a widget or send notifications

There's no client component to this module, it should be invoked by other modules.

### Configuration file:

If you want to filter out certain modules or certain type of events within modules, you can use a YAML config file. The file is loaded by default from:

`local/module_configs/event.yml`

You can override this file location by specifying the following variable:

`EVENT_CONFIG_PATH=/path/to/wherever/config.yml`

#### Example 1:
```
filter:
    diskreport:
```
This will filter out all `diskreport` messages

#### Example 2:
```
filter:
    diskreport:
    munkireport:
      - warnings
```
This will also filter out all `munkireport` messages of the type `warnings`

The available types for filtering are `danger`, `warning`, `success` and `info`.
