# Tab组件

`ShaoZeMing\Merchant\Widgets\Tab`类用来生成选项卡组件：

```php
use ShaoZeMing\Merchant\Widgets\Tab;

$tab = new Tab();

$tab->add('Pie', $pie);
$tab->add('Table', new Table());
$tab->add('Text', 'blablablabla....');

echo $tab->render();

```

`Tab::add($title, $content)`方法用来添加一个选项卡，`$title`为选项标题，`$content`选项卡内容。