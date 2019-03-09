# Changelog

## 2.0-1901 (26/01/2019)

---

## Vendor Folder for Git Release (24/12/2018)

---

## v2.0-1810w3a - Admin Panel Showcase/Bug Fixes (25/10/2018)
Hey all,

Need to push this out because of problems with smartCARS. Good news is that you're getting the new admin panel that's WIP.
---

## v2.0-1810w1a - Weekly Snapshot 2 | Admin Overhaul Pre-Release (05/10/2018)
Welcome to the first full pre-release with some brand new features (that are barely supported of the admin panel overhaul. This is where you get to tell me what you want changed or improved. Please note that all bugs are known.

Import/Export is being completely redesigned right now.
Users are still on the old panel design
Airports option has yet to be implemented
Hub adding, while fully supported by manually modifying the database, doesn't work yet.
And some other smaller things.

**The files in this release are released under the assumption you know what you're doing. If you don't know what you're doing, please don't download the snapshot releases. Thanks**
---

## v2.0-1810w1 - Weekly Snapshot (03/10/2018)
Please note that these files are not supported. If you download, you're downloading at your own risk.
---

## Material Crew Update (25/09/2018)
Fixed a few bugs and added some Vue.JS components to the interface. A must have update to tie you over until Beta 4.
---

## Beta 3a Airport Banner URL Fix (04/09/2018)

---

## 2.0 Beta 3 (01/09/2018)
:100: 
---

## 2.0 Beta 2 (23/08/2018)
Try this release. If you have issues, please let me know ASAP. This will, for now, require manual modifications of the files to work for shared hosting. However, all the resources are there.
---

## 2.0 Developer Preview 1 (25/07/2018)
This version of the system is not for the light hearted.... at all. Basically if you want to check out what's new from a functionality level, go for it. Got a fresh coat of paint on the interface. More code on the backside in the coming days and will be available in a few days.

**THIS VERSION WILL NOT BE SUPPORTED FOR AIRLINES TO USE. THAT WILL BE AVAILABLE IN NON DEVELOPER PREVIEW BUILDS**
---

## 1.0.3a Hotfix A (24/11/2017)
Hey Everyone,

New patch. Here's the change log.

- New Users are now Pending Status by Default. Middleware to check login status is coming in next patch. This will be changeable in the future in the .env file.
- Fixed bug with modals in CoreUI Admin Template
- PIREP/Logbook Pages are now browsable from Admin side along with management. Added quick reference to pending page and cleaned up misc code.
- Fixed issue where mobile users cannot login due to some Javascript.
- Fixed bug where if no smartCARS logs are present, the Javascript will error out before filling the flight status box at the top with the appropriate information.
- Fixed bad link to Logbook flight.
- Removed redundant PIREPs link within User Management. Fixed Javascript.
---

## Version 1.0.3 (17/11/2017)
Yet again we're doing another release. This time some minor changes, however some prep work for the larger 1.0.4 update.

**THIS RELEASE CONTAINS DATABASE CHANGES. ONCE FILES ARE IN PLACE, LOGIN TO YOUR ADMIN PANEL AND CLICK THE NEW DATABASE MIGRATIONS TAB TO RUN THEM**

- PIREP Pages are now a thing. You can now fully view the smartCARS Log along with additional stats.
- Live ACARS Telemetry is temporarily disabled (ie. Flight Maps via smartCARS now just return a flat `true`). Working to resolve those bugs and I may throw the changes in a hot release.
- Hubs missed the mark because they looked poorly implemented and not up to quality standards.
- Other minor fixes.
- PilotIDs make their soft return. You can now assign them via the admin panel to pilots. **NUMBERS ONLY PLEASE** as that's what the upcoming SimBrief API connection will be using.
---

## 1.0.2a Git Hub Versioning Issue Hotfix (18/10/2017)
Hey All,

Recently there was a GitHub mix up with some versioning (some things were and were not pushed properly) on the previous release. This fix should resolve all the errors currently being experienced with the system which should fix the error. Please note that this will require you to look within your database in order to determine if you need this hotfix as this, to my knowledge, only affected from scratch installs.

In your favorite MySQL DB browsing tool (phpMyAdmin or MySQL Workbench), look for the `vaos_migrations` table.

If you are missing the following migrations:
```
2017_01_11_044315_job_progress
2017_07_24_011502_user_modify    
2017_07_24_013111_airline_color
2017_08_14_195620_update_posion_report_table
2017_08_14_213055_update_pireps_table
2017_08_30_213055_add_acars_client_column
```
You will need to do the following:

1. Navigate to `/database/migrations`.
2. Remove the file named `2017_03_08_054906_fix_legacy_bids.php`
3. Login to VAOS and navigate to `/admin/migrate` which will re-run the database install and should resolve the previous ACARS table issue.

Additionally, if you have experienced any issues with smartCARS filing of PIREPs, it is advised you install an additional fix which should resolve your issue.

1. Download the entire Git Repo source via the links below.
2. Replace the following files with their new versions.
```
/app/Http/Controllers/LegacyACARS/smartCARS.php
/public/legacy_ACARS/smartCARS/interface.php
```


If you have any issues with the above, please reach out via the issues tab, or directly through the Discord Support Channel.

For those on Shared Hosting... Rejoice!!! I now have a shared hosting specific binary with all the URL editing done for you! Download that below.
---

## 1.0.2 Update (30/09/2017)
Ladies and gentleman, presenting the 1.0.2 release. Sorry for the long delay on this one. We have some very nice changes to help you out.

- smartCARS functionality has been rewritten when handling bids on filing PIREPs. This should fix isolated issues on shared hosting.
- smartCARS Logs and Fuel Used are now stored. The interface has not been setup yet to support it as it would throw errors; especially when XACARS does not track those data points.
- All other hotfixes.

**THIS RELEASE CONTAINS DATABASE SCHEMA UPDATES. PLEASE RUN LARAVEL MIGRATION TOOL.**

Shared Hosting: `http://yoursite.com/admin/migrate`
Command Line: `php laravel migrate`

## Shared Hosting Without Git

Please note that I did not compile a shared hosting binary just yet. This is due to me doing this on university computers. Therefore, modified install steps for installing from source:

- Backup your .env file and ANY CUSTOM FILES YOU HAVE MADE located in the VAOS folder.
- Remove all folders **EXCEPT FOR THE VENDOR FOLDER**
- Paste the new folders into your install.
- Migrate your modified files back into the release taking note of the changes between the files.
- Login to your website. Then, run `http://yoursite.com/admin/migrate` to update your database schema

## Dedicated and Shared Hosting With Git

- Backup your VAOS folder.
- simply `git pull` and you're done.
- migrate any modifications you have made to your files pertaining to the new update.
- Run `php laravel migrate` to update your schema.
---

## changed ident to gps_code (15/09/2017)
changed ident to gps_code in VAOS_Airports and in Airport Api to match the changes of the master server

Replace `/app/Classes/VAOS_Airports.php`  and `app\Http\Controllers\API\AirportsAPI.php` with the included Files.
---

## Legacy References Removal (30/08/2017)
Had to removed a few lines of code which was preventing bids from being cleared out of the system.

Replace `/app/Classes/VAOS_Schedule.php` with the included File.
---

## 1.0.1a Shared Hosting Hotfix (18/08/2017)
This problem is to address Laravel Migrations within the shared hosting environment. To use the Laravel Migration step which would normally be segregated off to ssh access:

- Make sure you're logged in
- type in the URL `http://yoursite.com/admin/migrate`

If you get no error, your database should now be updated.
---

## 1.0.1 (17/08/2017)
Ladies and gentleman, presenting the 1.0.1 release. After a week of the release, we flushed out some initial bugs that plagued the original release along with 2 MAJOR fixes.

- ACARS Live Map Now Works
- ACARS Tracking API Now Available
- A lot of interface fixes.

## Shared Hosting Without Git

- Backup your .env file and ANY CUSTOM FILES YOU HAVE MADE located in the VAOS folder.
- Drag and drop the new individual folders replacing the files inside
- Migrate your modified files back into the release taking note of the changes between the files.

## Dedicated and Shared Hosting With Git

- Backup your VAOS folder.
- simply `git pull` and you're done.
- migrate any modifications you have made to your files pertaining to the new update.
---

## Version 1.0 (08/08/2017)
The 1.0 Release is here.


---

## Beta 4 Cutting Edge (09/03/2017)
This is Beta 4 in it's current state with the new Vendor Folder.
---

## Beta 3 Release (29/01/2017)
Beta 3 brings some much needed improvements and completes the installer.
