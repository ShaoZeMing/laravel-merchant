# Infobox组件

`ShaoZeMing\Merchant\Widgets\InfoBox`类用来生成信息展示块：

```php
use ShaoZeMing\Merchant\Widgets\InfoBox;

$infoBox = new InfoBox('New Users', 'users', 'aqua', '/merchant/users', '1024');

echo $infoBox->render();

```

效果请参考后台首页的布局文件[HomeController.php](/src/Commands/stubs/ExampleController.stub)的`index()`方法中，关于`InfoBox`的部分。