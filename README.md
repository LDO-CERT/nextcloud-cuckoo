Cuckoo4nextcloud
========

**Plugin for [Nextcloud](https://nextcloud.com) for send files to cuckoo sandbox.**


Installation
------------

**Nextcloud**

Place this app in **nextcloud/apps/cuckoo** and then in you NC instance, simply navigate to »Apps«, choose the category »security«,
find the cuckoo app and enable it.

Remember (for now) to configure two variable with nextcloud ip/url

Under file: lib/Controller/CuckooController.php var name $cuckoo_api_url

Under file: js/cuckoo.tabview.js var name sandbox_http_url


Usage
-----

Just open the details view of the file (Sidebar). There should be a new tab called "Cuckoo" with button for send it to sandbox

Compatibility
-------------

- This is a "quick&dirty" apps, I need time to improve code...  ;-)
