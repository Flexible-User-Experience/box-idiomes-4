Changelog
=========

##### Version 4.5.06 (WIP)
 * fix minor bugs

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
