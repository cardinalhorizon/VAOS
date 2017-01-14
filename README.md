![alt text](http://fsvaos.net/img/VAOSLarge.png)

# About

The Virutal Airline Operations System project is a open source and modern Virutal Airline Content Mangagement System developed for use in flight simulators such as Microsoft Flight Simulator, Prepar3D and X-Plane. Development of the system started in late 2014.


# System Requirements

PHP: 5.6

MySQL: 5.3

Apache 2.2 or latest nginx version.


# Getting Started

## Dedicated Servers (SSH Access)
> This will also work on Shared Hosts with SSH Access

Using SSH, run the following commands
```
$ git clone https://github.com/CardinalHorizon/VAOS/
$ cd VAOS
$ composer install
$ php artisan key:generate
$ php artisan migrate
```
If required to use smartCARS support, please run the following command.

```
$ php artisan vaos:installphpVMS
```
## Shared Hosting (cPanel)

If you are using one of the official hosting partners, Instructions will be provided pertaining how they will be distributing VAOS on their system.
