## CHANGELOG FOR 1.0.0-beta.5
- feature #974 Update Symfony packages (piotrkreft)
- feature #980 Update nelmio cors-bundle (wfajczyk)
- refactor #951 Removed from field from events (piotrkreft)
- refactor #970 Change interface in shopware6 export (wfajczyk)
- refactor #972 Standardized date formatting on grid (piotrkreft)
- refactor #977 Shopware6 customfield (wfajczyk)
- improvement #960 Add mass product (wfajczyk)
- bugfix #964 Adding langauge to product has status condition (wiewiurdp)
- bugfix #975 Shopware6 multimedia create/duplicate name problem (wfajczyk)
- bugfix #973 Fixing ProductAttribute Relationshop streategy (wiewiurdp)
- bugfix #981 Remove unnecesery "has status" condition from workflow (rprzedzik)
- bugfix #983 Add missing DeleteProductEventProjector (rprzedzik)
- bugfix #984 Fix remove completness information after product deletion (rprzedzik)
- bugfix #987 Shopware6 Customfield Translate (wfajczyk)
- bugfix #991 Fix completnes query (rprzedzik)

## CHANGELOG FOR 1.0.0-beta.4
- bugfix #965 clear old data currency and tax in db (wfajczyk)
- bugfix #961 Empty root value (wfajczyk)
- bugfix #959 Fixing product has status condition (wiewiurdp)
- feature #954 Shopware 6 error list (wfajczyk)
- bugfix #953 Record calculation in import process (rprzedzik)
- refactoring #946 Param converter refactoring (wiewiurdp)
- bugfix #949 Readme type (piotrkreft)
- configuration #946 Switched to GitHub actions (piotrkreft)
- refactoring #943 Remove transformer module (rprzedzik)
- bugfix #944  fix create status for imported, product on edit page (rprzedzik)
- bugfix #929 Remove CPD and MD (BastekBielawski)
- refactoring #895 Refactor(all)Changes to be compliant with Phpstan level 2  (wiewiurdp)
- bugfix #930 fix OptionKey value type cast in magento1 import (rprzedzik)

## CHANGELOG FOR 1.0.0-beta.3
- feature [#902](https://github.com/ergonode/backend/issues/882)  Batch product remove action(rprzedzik)
- feature #913 Moved Nelmio configuration to appropriate modules (piotrkreft)
- bugfix #900 Fixing template field in grid of associated products (wiewiurdp)
- bugfix #895 calculate completeness strategy (wfajczyk)
- bugfix export shopware6 multimedia and unit mapper (wfajczyk)
  
## CHANGELOG FOR 1.0.0-beta.2
- feature [#882](https://github.com/ergonode/backend/issues/882) Adding JWT refresh (wfajczyk)
- feature change static check code(piotrkreft)
- feature #885 Add translated import error mesages (rprzedzik)
- bug #866 Changing endcoding for user password(wiewiurdp)
- bugfix #887 import process (rprzedzik)
- configuration #855 Extend JSONContext to work with modules(rprzedzik)

## CHANGELOG FOR 1.0.0-beta
- feature [#803](https://github.com/ergonode/backend/issues/688) Multilingual workflow (rprzedzik) (wiewiurdp) (wfajczyk)
- refactor [#776](https://github.com/ergonode/backend/issues/775) Moved persistence directory into infrastructure (BastekBielawski)
- feature[#730](https://github.com/ergonode/backend/issues/730) Autocomplete endpoints (wiewiurdp)
- refactor [#737](https://github.com/ergonode/backend/issues/737) Transfer behat to core module (wfajczyk)
- refactor [#766](https://github.com/ergonode/backend/issues/766) Product with variants management optimization (wiewiurdp)
- feature [#672](https://github.com/ergonode/backend/issues/672) Dashboard widget information endpoints (piotrkreft)(rprzedzik)
- feature [#688](https://github.com/ergonode/backend/issues/688) Cyclical launch of exports per channel (rprzedzik)
- feature #758 Add mailer module (BastekBielawski)
- feature #765 Serializing dates using ISO8601 standard (piotrkreft)
- feature #747 Introduced mutation testing (piotrkreft)
- feature #748 Formatted behat output with progress (piotrkreft)
- feature #757 Adding role name unique validation (wiewiurdp)
- feature #755 Adding behat test for textarea attribute (wiewiurdp)
- feature #761 Fixed Phing PHPUnit targets (piotrkreft) 
- feature #760 Added timezone offset info to database dates (piotrkreft)
- feature #763 Overridable configuration files (piotrkreft)
- feature #769 Healthcheck static file (piotrkreft)
- feature #772 Travis optimization (piotrkreft)
- feature #771 Bumped Symfony packages (piotrkreft)
- feature #774 Composer validation (rprzedzik)
- feature #784 Sorting counted statuses (piotrkreft)
- feature #800 Merged modules migrations (piotrkreft)
- feature #806 Storing id in JWT instead of email address (piotrkreft)
- feature #805 shopware6 change custom field mapper (wfajczyk)
- feature #821 delete action (wfajczyk)
- feature #824 Extended status count with possibility to differentiate workflow by language (piotrkreft)
- feature #826 change filename limit and add validator (wfajczyk)
- feature #828 change max legenth (wfajczyk)
- feature #834 shopware6 mapper (wfajczyk)
- feature #835 Product form unification (piotrkreft)
- bugfix #742 Fixed invalid phpstan/phpstan version (piotrkreft)
- bugfix #756 Fixed infection/infection dependency as dev (piotrkreft)
- bugfix #762 Fixed typo in README.md  (piotrkreft)
- bugfix #811 Recalculating segment on product creation (piotrkreft)
- bugfix #819 Fixed option condition (piotrkreft)
- bugfix $827 Translation typo (piotrkreft)
- bugfix #825 fix incorrect event handling (rprzedzik)
- bugfix #830 Validation error on missing product type on product creation (piotrkreft)
- bugfix #833 fix incorrect widget calculation (rprzedzik)
- bugfix #839 fix completeness calculation (rprzedzik)
- bugfix #731 role entity (lexthink)
- bugfix #768 Fixing name and symbol unique validation (wiewiurdp)
- bugfix #799 Fixed sorting of count statuses (piotrkreft)
- bugfix #801 Fixed workflow transition sorting (piotrkreft)
- bugfix #802 filter created by and edited by (wfajczyk)
- bugfix #785 Fixing unique role name validation (wiewiurdp)
- bugfix #770 Changing role description validation to not required (wiewiurdp)
- bugfix #807 Changing validation to work with updating units (wiewiurdp)
- bugfix #809 unifying language autocomplete with rest autocompletes (wiewiurdp)
- bugfix #818 fixing bugs:createdAt and EditedAt in grid (wiewiurdp)
- bugfix #813 Fixing unique multiselect option and empty option (wiewiurdp)
- bugfix #831 adding delete link to associated grid (wiewiurdp)
- bugfix #832 fixes category code validate message (wiewiurdp)
- bugfix #838 Changing Date columns and filters in grids (wiewiurdp)
- bugfix #837 Adding color to status count endpoint (wiewiurdp)
- bugfix #817 remove not use projection (wfajczyk) 
- refactor #752 change import magento 1 process (rprzedzik)
- performance #745 database clean (wfajczyk)

## CHANGELOG FOR 0.10.0
- feature [#688](https://github.com/ergonode/backend/issues/688) Cyclical launch of exports per channel(rprzedzik)
- feature [#681](https://github.com/ergonode/backend/issues/681) EventStore snapshot support (rprzedzik)
- feature [#654](https://github.com/ergonode/backend/issues/654) Export product with variants to Shopware 6 (wfajczyk)
- feature [#664](https://github.com/ergonode/backend/issues/664) Change Language Code Standard (wfajczyk)
- feature [#657](https://github.com/ergonode/backend/issues/657) Add image thumbnail generation (rprzedzik)
- feature [#656](https://github.com/ergonode/backend/issues/656) Add file type/group information (rprzedzik)
- feature [#650](https://github.com/ergonode/backend/issues/650) Csv system exporter (rprzedzik)
- feature [#636](https://github.com/ergonode/backend/issues/636) Category management in the Product(wfajczyk)
- feature [#633](https://github.com/ergonode/backend/issues/633) Adding type to Category (wiewiurdp)
- feature [#625](https://github.com/ergonode/backend/issues/625) Gallery and File attribute (rprzedzik)
- feature [#612](https://github.com/ergonode/backend/issues/612) Avatar resource separation (wiewiurdp)
- improvement [#679](https://github.com/ergonode/backend/issues/679) Import error list (wfajczyk)
- improvement [#674](https://github.com/ergonode/backend/issues/674) Adding language translation to Shopware6 export (wfajczyk)
- improvement [#669](https://github.com/ergonode/backend/issues/669) Extend csv export with option, template and multimedia (rprzedzik)
- improvement [#652](https://github.com/ergonode/backend/issues/652) Adding product net price to Shopware 6 Export (wiewiurdp)
- improvement [#651](https://github.com/ergonode/backend/issues/651) Adding language synchronization for export (wiewiurdp)
- improvement [#644](https://github.com/ergonode/backend/issues/644) Adding possibility choosing many languages to shopware6 export (wiewiurdp)
- improvement [#528](https://github.com/ergonode/backend/issues/528) Shopware6 Export Module (wfajczyk)
- refactor [#631](https://github.com/ergonode/backend/issues/631) Abstract Module refactor (wiewiurdp)
- refactor [#627](https://github.com/ergonode/backend/issues/627) Event store cache separation (wiewiurdp)

## CHANGELOG FOR 0.9.0
- feature [#607](https://github.com/ergonode/backend/issues/607) Rich Text Editor (wiewiurdp)
- feature [#566](https://github.com/ergonode/backend/issues/566) Adding Tile view for grids  (wiewiurdp)
- feature [#517](https://github.com/ergonode/backend/issues/517) Add product with associations (rprzedzik)
- feature [#574](https://github.com/ergonode/backend/issues/574) Refactoing: template id as product property  (rprzedzik)
- experimental [#576](https://github.com/ergonode/backend/issues/576) Multimedia managment (rprzedzik)
- fix [#585](https://github.com/ergonode/backend/issues/585) Workflow status changing error on product page (rprzedzik)
- improvement [#374](https://github.com/ergonode/backend/issues/374) Export Profile (wfajczyk)

## CHANGELOG FOR 0.8.0
- feature [#504](https://github.com/ergonode/backend/issues/504) Language inheritance (rprzedzik)
- feature [#505](https://github.com/ergonode/backend/issues/505) Language privileges  (wiewiurdp)
- feature [#502](https://github.com/ergonode/backend/issues/502) Language tree inheritance (wfajczyk)
- feature Adding tile grid functionality on template grid (wiewiurdp)

## CHANGELOG FOR 0.7.0
- feature [#343](https://github.com/ergonode/backend/issues/343) Add product collection module (wiewiurdp)
- feature [#335](https://github.com/ergonode/backend/issues/335) [#391](https://github.com/ergonode/backend/issues/391) Extend condition set (Daniel-Marynicz,wfajczyk)
- feature [#496](https://github.com/ergonode/backend/issues/496) Extensions of unit (wiewiurdp) 
- experimental [#367](https://github.com/ergonode/backend/issues/367) Basic Import CSV File from Magento 1 (rprzedzik)
- experimental [#378](https://github.com/ergonode/backend/issues/378) Creating Shared Kernel (wfajczyk)
- experimental [#351](https://github.com/ergonode/backend/issues/351) New export space in DB (wfajczyk)
- experimental [#328](https://github.com/ergonode/backend/issues/328) Add channel module (rprzedzik)
- experimental [#374](https://github.com/ergonode/backend/issues/374) Create Export Skeleton (wfajczyk)
- fix [#397](https://github.com/ergonode/backend/issues/397) Merge category tree to category (wfajczyk)
- improvement [#331](https://github.com/ergonode/backend/issues/331) Update PHP to 7.4 (nimah79,Daniel-Marynicz,rprzedzik,wfajczyk,wiewiurdp) 
- improvement [#322](https://github.com/ergonode/backend/issues/332) Upgrade to Symfony 4.4 (Daniel-Marynicz)
- improvement [#421](https://github.com/ergonode/backend/issues/421) Upgrade PHPUnit to 9.0 (Daniel-Marynicz)
 
## CHANGELOG FOR 0.6.1
 - fix - Add missing date filter for grid (wfajczyk)
 - fix - Add missing date format validator (wfajczyk)
 - fix - Validate data for numeric type (rprzedzik)
 - fix - Filtering of data in the grid for a select filter (wiewiurdp)
 - fix - Change template element type form TEXTAREA to TEXT_AREA (rprzedzik)

## CHANGELOG FOR 0.6.0 
 - feature - Add product history log
 - feature - Add core notifications 
 - feature - Add workflow notifications
 - feature - Add comment module 
 - feature - Add system attribute
 - improvement - Grid query optimisation, add advanced filter support
 - fix - .env according to Symfony 4.3 
 - improvement - Behat optimisation and added more tests
 - improvement - Upgrade Code Sniffer
 - experimental - Add deptrac
 - experimental - Remove GfreeauGetJWTBundle 
 - improvement - Merge attribute to one module
 - improvement - Change domain events to work with Symfony Message component
  
## CHANGELOG FOR 0.5.0
 - feature [#115](https://github.com/ergonode/backend/issues/115) Product segment functionality (rprzedzik)
 - feature [#118](https://github.com/ergonode/backend/issues/118) Event store history (BastekBielawski)
 - feature [#124](https://github.com/ergonode/backend/issues/124) Register events in database (BastekBielawski)
  
## CHANGELOG FOR 0.4.0

 - feature [#104](https://github.com/ergonode/backend/issues/104) Multiple category trees (wiewiurdp)
 - feature [#98](https://github.com/ergonode/backend/issues/98) Add Workflow Transitions (rprzedzik)
 - feature [#77](https://github.com/ergonode/backend/issues/77) User activity flag (BastekBielawski)
 - improvement [#76](https://github.com/ergonode/backend/issues/76) JMS interface handler (BastekBielawski)
 - feature [#73](https://github.com/ergonode/backend/issues/73) Add product status (rprzedzik)
 - feature [#71](https://github.com/ergonode/backend/issues/71) Language configuration (wiewiurdp)
 - experimental [#90](https://github.com/ergonode/backend/issues/90) Remove FOSRestBundle (BastekBielawski)
 - improvement Upgrade to Symfony 4.3 (BastekBielawski)
 
## CHANGELOG FOR 0.3.0

 - feature [#39](https://github.com/ergonode/backend/issues/39) Add Roles and privileges (rprzedzik)
 - feature [#39](https://github.com/ergonode/backend/issues/41) Refactoring user authentication (BastekBielawski)
 - feature [#42](https://github.com/ergonode/backend/issues/42) User activity log (rprzedzik)
 - improvement [#51](https://github.com/ergonode/backend/issues/51) Improvements in roles and privileges (BastekBielawski)
 - feature [#54](https://github.com/ergonode/backend/issues/54) Add all users worklog grid endpoint (rprzedzik)
 - experimental [#60](https://github.com/ergonode/backend/issues/60) Deepl - Automatic translations (wiewiurdp)
  
## CHANGELOG FOR 0.2.0

- feature [#1](https://github.com/ergonode/backend/issues/1) Template designer - layout improvement (rprzedzik)
- bugfix [#2](https://github.com/ergonode/backend/issues/2) Bugfix - missing console file (bleto)
- bugfix [#13](https://github.com/ergonode/backend/issues/13) Bugfix - missing key exists checking (rprzedzik)
- feature [#17](https://github.com/ergonode/backend/issues/17) Category tree - add api endpoints (rprzedzik)
- bugfix [#19](https://github.com/ergonode/backend/issues/19) Missing DefaultTemplateGenerator class (rprzedzik)
- feature [#26](https://github.com/ergonode/backend/issues/26) Separating grids rendering mechanism (rprzedzik)
- feature [#31](https://github.com/ergonode/backend/issues/31) Add Category tree get endpoint (rprzedzik)
- configuration [#36](https://github.com/ergonode/backend/issues/36) Add phpstan (rprzedzik)

## CHANGELOG FOR 0.1.1

- hotfix [#12](https://github.com/ergonode/backend/issues/12) Hotfix - Duplicate projection call (rprzedzik)
