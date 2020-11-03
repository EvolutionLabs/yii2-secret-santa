# yii2-secret-santa

This module is creating pairs from a list of email and is sending emails to the chosen Santas


#### Installation


```shell script
composer require evolutionlabs/yii2-secret-santa
```

Add this to your composer.json
``` 
"repositories": [
   {
       "type": "vcs",
       "url": "git@github.com:EvolutionLabs/yii2-secret-santa.git"
   }
]
```
#### Usage

Add the component to your main.php.
```php
'modules' => [
        'secret-santa' => [
            'class'                   => \evolutionlabs\ssanta\SecretSanta::class,
            'useDefaultEmailSolution' => true,
            'emailTemplate'           => '@evolutionlabs/ssanta/mail/notify-giver',
            'logo'                    => '/img/logos/logo-carturesti.png'
        ]
    ];
```
Run the migrations:

```php 
yii migrate/up --migrationPath=@evolutionlabs/ssanta/console/migrations
```
Access at: /secret-santa/lists/index


