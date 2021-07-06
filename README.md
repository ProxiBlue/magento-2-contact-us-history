# ContactUsHistory

**ContactUsHistory**

Module saving data from "Contact Us" form to db and show it via grid at admin panel.

Also module provides functionality for reply on customer note
from admin panel and admin notification on new note placing.

Module compatible with magento 2.2 and will be with magento 2.3.

To install module type:

  `composer require vitaliyboyko/contact-us-history`
  
  `php bin/magento module:enable VitaliyBoyko_ContactUsHistory`
  
  `php bin/magento setup:upgrade`

After install you can found grid in adminpanel - Marketing / Communications / Contact form notes

Added in this version:

* form_id: hidden field in forms to populate and filter by forms, example: contact us, quote form etc.
* form_data: json form data to capture any custom form fields.
* 2.4 compatibility
