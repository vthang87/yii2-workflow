Yii2 Workflow
=============
LCS Soft Yii2 Workflow

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vthang87/yii2-workflow "*"
```

or add

```
"vthang87/yii2-workflow": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```bash
./yii migrate --migrationPath=@vthang87/workflow/migrations
```
Add module to web config 
```php
    modules'    => [
		'workflow'   => [
			'class' => 'vthang87\workflow\Module',
		],
		....
	],
```
Attach behavior to activite record model
```php
public function behaviors()
{
    return [
        'vthang87\workflow\behavior\WorkflowBehavior',
    ];
}
```
Manager workflow 
```html
http://localhost/path/to/index.php?r=workflow
```
To get next status list 
```php
vthang87\workflow\helpers\WorkflowHelper::getNextStatusList($model, $column, $id_status)
```

To get all status list 
```php
vthang87\workflow\helpersWorkflowHelper::getAllStatusList($model, $column)
```