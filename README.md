Kayako WHMCS Integration
=======================

This library is maintained by Kayako.

Overview
=======================

Kayako module WHMCS integration for Kayako version 4.

It provides us a module that can be integrated with WHMCS to facilitate users in ticket creation, view ticket and update the created ticket at WHMCS interface.

Users can also find answers for your queries within Knowledgebase, which is provided within integrated module of Kayako for WHMCS.

Features
=======================
* Integrate WHMCS user account with Kayako Helpdesk.
* User can open a new ticket with any priority (Low, Medium, High, Critical, Emergency).
* Provides list of all tickets created by user.
* User can update the ticket status and priority.
* User can follow up with his/her tickets via ‘Post Reply’.
* Field sorting is provided to sort data as per requirement.
* Integrated with ‘Kayako Knowledgebase’ to help user in finding answers for their queries.
* Users can submit their respective feedback for knowledgebase articles.
* Kayako custom field support is provided.

Supported versions
=======================
* Kayako: 4.64 and above
* WHMCS: 5.1.2 and above

Installation Steps
=======================
1. Download and extract the Kayako-WHMCS integration
2. Copy 'modules/support/kayako/' folder from 'src/modules' and paste under 'modules/support/kayako/' directory of installed_WHMCS
3. Copy 'templates/kayako/' folder from 'src/templates' and paste under 'templates' directory of installed_WHMCS
4. Copy 'hooks.php' from 'includes/hooks/' folder and paste it in 'installed_WHMCS/includes/hooks' folder. (if 'hooks.php' already exists, then please add this code in respective file at the end)
5. Copy API Details from your Helpdesk installation > Rest API > API Information. Now open installed_WHMCS/modules/support/kayako/config.php and make respective changes for your API details
6. Modify date format in Installed_WHMCS/modules/support/kayako/API/kyConfig.php as per the date format you specified in installed_WHMCS/modules/support/kayako/config.php
7. Go to Admin area of WHMCS installation
8. Go to Setup > General Settings
9. Click on Support tab and select 'Kayako' from dropdown of 'Support Module'
10. Now you are ready to use 'WHMCS integration with Kayako'