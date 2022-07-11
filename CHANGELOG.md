Changelog
=========

##### Version 5.2.13 (WIP)
 * fix minor bugs

##### Version 5.2.12 (2022-07-11)
 * show zero line in Dashboard chart
 * fix sonata-project/admin-bundle v4.12.0 to avoid missing ProxyQueryInterface servive on batch actions
 * fix nullable getDiscount problem in GenerateReceiptItemModel
 * cleanup yaml config files after flex recipes upgrade

##### Version 5.2.11 (2022-06-27)
 * Symfony 5.4.10 security update
 * set PHP cookie_lifetime to zero, bind to user browser opened time
 * yarn vendors update

##### Version 5.2.10 (2022-06-13)
 * add reCaptcha in PreRegister form
 * remove list__action_create_student_from_pre_register_button fron PreRegister admin
 * add enabledString in PreRegister export

##### Version 5.2.09 (2022-06-13)
 * composer & yarn vendors update
 * improve frontend PreRegister september feature

##### Version 5.2.08 (2022-06-10)
 * composer & yarn vendors update
 * enable frontend PreRegister september feature

##### Version 5.2.07 (2022-05-30)
 * add Student admin tariff helper
 * composer & yarn vendors update

##### Version 5.2.06 (2022-04-28)
 * fix critical problem setting a service injection in entity
 * add DNI into invoice export admin
 * Symfony 5.4.8 update

##### Version 5.2.05 (2022-04-05)
 * remove some hardcoded date formats
 * remove hardcoded enterprise name
 * fix Abstract PDF builders
 * composer & yarn vendors update

##### Version 5.2.04 (2022-04-01)
 * improve config CS files
 * upgrade EWZRecaptcha up to v3
 * add export filename parameter
 * add discount per extra son parameter
 * remove hardcoded project web title from email subjects
 * remove hardcoded project web title from templates
 * fix bad data-gcal-api-key attribute name
 * remove hardcoded city from mail base & PDF builder

##### Version 5.2.03 (2022-03-31)
 * composer & yarn vendors update

##### Version 5.2.02 (2022-03-10)
 * usage of boolean string conversion in Receipt & Invoice export action admins

##### Version 5.2.01 (2022-03-10)
 * add parent payment datagrid filtes in Receipt & Invoice admins

##### Version 5.2.00 (2022-03-10)
 * add parent payment filter in Student Admin
 * improve Student show view

##### Version 5.1.07 (2022-03-08)
 * fix error on sending student absence action button

##### Version 5.1.06 (2022-03-08)
 * show payment excemption & internal regulations attr Student details

##### Version 5.1.05 (2022-03-07)
 * Symfony 5.4.6 update
 * fix pdfjs-dist version to 2.12.313 due to problem after yarn auto upgrade

##### Version 5.1.04 (2022-03-03)
 * fix ZIP generation during ReceiptAdminController action

##### Version 5.1.03 (2022-03-02)
 * change to DateRangeFilter in SpendingAdmin

##### Version 5.1.02 (2022-03-01)
 * Symfony 5.4.5 update
 * remove unnecessary JS console log outputs
 * hide email & phone attr from StudentAdmin & PersonAdmin

##### Version 5.1.01 (2022-02-09)
 * fix emails rendering
 * remove hardcoded GoogleCharts unused dependency in Admin dashboard view
 * fix student calendar show view problem

##### Version 5.1.00 (2022-02-09)
 * apply danger class in admin delete icon action buttons
 * add reset password behaviour
 * fix mail templates problem

##### Version 5.0.02 (2022-02-08)
 * fix User salt attribute null field

##### Version 5.0.01 (2022-02-08)
 * fix Ansible broken deploy process
 * set monolog rotating files strategy

##### Version 5.0.00 (2022-02-08)
 * Symfony 5.4 LTS upgrade
 * Sonata Admin 4.0 upgrade
 * Bootstrap CSS 5.0 upgrade
 * Font Awesome 5.0 upgrade
 * add Admin XLSX export format
 * improve frontend responsiveness
 * improve frontend Google Map in contact view
 * improve backend dashboard Google Chart 3.0
 * improve backend dashboard Full Calendar 5.0
 * add better admin PDF previews
 * replace base amount by amount Spending attribute name
 * add mark as payed batch action in ReceiptAdminController
 * add Provider tax identification number in Spending admin export action

##### Version 4.7.17 (2022-01-20)
 * fix missing download PDF button template in SpendingAdmin edit view

##### Version 4.7.16 (2022-01-11)
 * add new credit card payment method in SpendingAdmin
 * change config IBAN value

##### Version 4.7.15 (2021-12-02)
 * vendors update
 * improve contact warning message on missing student case

##### Version 4.7.14 (2021-12-01)
 * vendors update
 * remove unnecessary contact warning message on missing student case

##### Version 4.7.13 (2021-10-26)
 * fix problem with sepaXmlGeneratedDate datagrid filters
 * fix charts problem related with date intervals

##### Version 4.7.12 (2021-09-30)
 * fullcalendar 4.4.2 update
 * add roave/security-advisories dev dependency
 * fix export problem

##### Version 4.7.11 (2021-09-29)
 * add better WhatsApp icon in CTA button
 * fullcalendar 4.4.2 update
 * add roave/security-advisories dev dependency

##### Version 4.7.10 (2021-09-28)
 * replace callto by tel tag
 * add Whatsapp call to action button
 * hide frontend homepage newsletter form section

##### Version 4.7.09 (2021-09-13)
 * add better Student abscence management with Stimulus controllers
 * disable preregister_period
 * fix typo in service parameter name

##### Version 4.7.08 (2021-09-13)
 * vendors update
 * add Classroom 6
 * draw ExportCalendarToListBuilderPdf in protrait mode and up to 7 classroom columns
 * remove 6:00 AM from admin dashboard calendar

##### Version 4.7.07 (2021-07-01)
 * fix broken PDF services

##### Version 4.7.06 (2021-07-01)
 * make only enabled students available to create/update a Event (classroom/timetable)

##### Version 4.7.05 (2021-06-30)
 * add new TariffType enums

##### Version 4.7.04 (2021-06-30)
 * fix bad student receipt generation due to missing ID join in where clause
 * fix broken tests
 * enable delete receipt action button
 * add better type hints in method signatures
 * dependencies update

##### Version 4.7.03 (2021-06-28)
 * open PreRegister form period (winter 2021)

##### Version 4.7.02 (2021-06-21)
 * Symfony 4.4.25 security update
 * Yarn dependencies upgrade
 * close PreRegister form period (summer 2021)

##### Version 4.7.01 (2021-04-12)
 * fix missing Apache reload hook after Ansible deploy
 * add short translations for long PreRegister attributes
 * improve ClassGroup results in StudentAdmin filter by school year

##### Version 4.7.00 (2021-04-12)
 * reduce unnecessary commented code lines
 * better local-php-security-checker usage
 * add Book filter in Student Admin
 * add hasBeenPreviousCustomerString attr in PreRegister entity
 * add wantsToMakeOfficialExamString attr in PreRegister entity
 * enable PreRegister form again (summer 2021)

##### Version 4.6.02 (2021-03-22)
 * fix Calendar PDF group hour ranges

##### Version 4.6.01 (2021-03-19)
 * improve Calendar PDF design

##### Version 4.6.00 (2021-03-19)
 * fix broken Travis tests
 * add new search Student by age datagrid filter
 * add new search Student by ClassGroup datagrid filter
 * add Calendar to PDF export action

##### Version 4.5.05 (2021-02-11)
 * make bankCreditorSepa attr always required in Student and Parent admin edit views
 * remove local-php-security-checker from Travis setup

##### Version 4.5.04 (2021-02-11)
 * add php-cs-fixer precommit git hook
 * update S8 Ansible host configuration
 * apply php-cs-fixer

##### Version 4.5.03 (2021-02-11)
 * Symfony 4.4.19 update
 * Yarn vendors update

##### Version 4.5.02 (2020-11-30)
 * fix exceeds the allowed maximum length in SEPA XML MndtId field

##### Version 4.5.01 (2020-11-30)
 * Symfony 4.4.17 update
 * change IBAN business .env.prod parameter

##### Version 4.5.00 (2020-11-24)
 * fix bad title in admin dashboard balance amounts
 * add multiple SEPA bank accounts generations

##### Version 4.4.00 (2020-11-18)
 * remove some deprecations
 * add reCaptcha in newsletter contact form
 * vendors update

##### Version 4.3.03 (2020-10-30)
 * Symfony 4.4.16 update
 * add new special TariffType enum

##### Version 4.3.02 (2020-10-22)
 * remove white spaces from callto number in frontend layout
 * fix broken PDF generator
 * add new classroom enum

##### Version 4.3.01 (2020-10-11)
 * decouple hardcoded brand phone numbers
 * remove removeAt default attr in all entities

##### Version 4.3.00 (2020-10-05)
 * enable Bank account number validation
 * remove some hardcoded customer brand data
 * improve dev mailer config

##### Version 4.2.06 (2020-10-04)
 * composer update
 * yarn upgrade
 * fix chart overflow in admin dashboard panel

##### Version 4.2.05 (2020-09-29)
 * remove preregister module from frontend top menu conditionally

##### Version 4.2.04 (2020-09-29)
 * Symfony 4.4.13 update

##### Version 4.2.03 (2020-09-25)
 * fix Students admin remove to erase previously related receipt and invoice relations

##### Version 4.2.02 (2020-09-19)
 * make XML SEPA generation less strict
 * remove unnecessary maker bundle

##### Version 4.2.01 (2020-09-15)
 * fix SonataUser role admin problem
 * composer & node vendors update

##### Version 4.2.00 (2020-09-13)
 * add admin conditonal delete student button & action

##### Version 4.1.10 (2020-09-09)
 * fix missing EWZRecaptcha Twig form template config
 * fix broken testing suite

##### Version 4.1.09 (2020-09-07)
 * Symfony 4.4.13 security update
 * Remove deprecated SonataCore bundle

##### Version 4.1.08 (2020-08-08)
 * Symfony 4.4.11 update
 * SonataUserBundle 4.7.0 update
 * fix problem to avoid problem storing emoji unicodes into MySQL

##### Version 4.1.07 (2020-06-26)
 * fix problem with null dates converted to string
 * fix typo

##### Version 4.1.06 (2020-06-25)
 * remove frontend PreRegister summer tab
 * enable frontend PreRegister september tab

##### Version 4.1.05 (2020-06-08)
 * yarn upgrade
 * fix Invoice mailer notification problem
 * fix mailer config problem

##### Version 4.1.04 (2020-06-06)
 * vendors update
 * annotate Receipt batch action problem on large input forms

##### Version 4.1.03 (2020-06-01)
 * remove frontend PreRegister september tab

##### Version 4.1.02 (2020-05-27)
 * make contact message admin management available to ROLE_MANAGER

##### Version 4.1.01 (2020-05-27)
 * finish new pre register form feature

##### Version 4.1.00 (2020-05-26)
 * add new pre register form feature

##### Version 4.0.00 (2020-05-23)
 * finish migration from Symfony 2.8

##### Version 0.0.05 (2020-05-23)
 * finish new vendors config after upgrade process

##### Version 0.0.04 (2020-05-21)
 * config new vendors after upgrade process

##### Version 0.0.03 (2020-05-20)
 * fix bad references after Symfony 2.8 migration

##### Version 0.0.02 (2020-05-15)
 * migrations from Symfony 2.8
 
##### Version 0.0.01 (2020-05-13)
 * create Symfony 4.4 LTS empty project
