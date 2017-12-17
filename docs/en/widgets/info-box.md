# Infobox

The `ShaoZeMing\Merchant\Widgets\InfoBox` class is used to generate the information presentation block:

```php
use ShaoZeMing\Merchant\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/merchant/users', '1024');

echo $infoBox->render();

```

Refer to the section on the `InfoBox` in the` index()`method of the home page layout file [HomeController.php](/src/Commands/stubs/ExampleController.stub).