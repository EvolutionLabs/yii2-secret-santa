# yii2-secret-santa

This module is creating pairs from a list of email and is sending emails to the chosen Santas


#### Installation


```shell script
composer require evo/yii2-secret-santa
```

#### Usage

Add the component to your main.php.
```php
'modules' => [
        'secret-santa' => [
            'class'                   => \evo\ssanta\SecretSanta::class,
            'useDefaultEmailSolution' => true,
            'emailTemplate'           => '@ssanta/mail/notify-giver',
            'logo'                    => '/img/logos/logo-carturesti.png'
        ]
    ];
```
Run the migrations:

```php 
yii migrate/up --migrationPath=@evo/ssanta/console/migrations
```
Access at: /secret-santa/lists/index


