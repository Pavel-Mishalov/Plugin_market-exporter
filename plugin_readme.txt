=== Market Exporter ===
Contributors: vanyukov
Donate link: http://yasobe.ru/na/market_exporter
Tags: market, export, yml, woocommerce, yandex market 
Requires at least: 4.0.0
Tested up to: 4.6.1
Stable tag: 0.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Плагин для экспорта товарных предложений из WooCommerce в YML файл для Яндекс Маркет.

== Description ==

= Русский =
Если Вы используете WooCommerce и хотите экспортировать все Ваши товары в Яндекс Маркет, то этот плагин однозначно для Вас! Market Exporter предоставляет возможность создавать файлы YML для экспорта товаров в Яндекс Маркет.

Плагин находится в активной разработке, которая поддерживает только упрощенный тип описания для экспортированного списка товарных предложений (т.е. выгружаются следующие поля: название, описание, цена, категория и изображение). Большой упор сделан на соответствие требованием Яндекс Маркет. Поддерживаются четыре валюты: рубль, гривна, доллар и евро.

Я собираю отзывы и предложения о том какой функционал Вы хотите видеть в плагине.

= English =
Are you using WooCommerce and want to export all your products to Yandex Market? Then this plugin is definitely for you! Market Exporter gives you the ability to create a valid YML file for Yandex Market.

This is the first release of the plugin. For now it can export only using the basic offer format which includes these product fields: title, description, price, url, picture. It supports unlimited amount of categories and subcategories.

Supported functions:
- Custom store and company name;
- Custom properties for next elements: vendor, market_category;
- Support for sales_notes element;
- Support for the following currencies: RUB, UAH, USD and EUR;
- Support for products on backorder;
- Product images in JPEG or PNG format;

== Installation ==

= Русский =
1. Загрузите 'Market Exporter' в папку с плагинами на Вашем сайте WordPress (/wp-content/plugins/).
2. Активируйте 'Market Exporter' через раздел 'Плагины' в WordPress.
3. Выберите 'Market Exporter' в разделе 'Инструменты' в WordPress.
4. Нажмите кнопку 'Генерировать YML файл'.

= English =
1. Upload 'Market Exporter' plugin to your WordPress website (`/wp-content/plugins/`).
2. Activate 'Market Exporter' through the 'Plugins' menu in WordPress.
3. Select 'Market Exporter' under the 'Tools' menu in WordPress.
4. Click on 'Generate YML file' button.

That's it! After the export process completes, you will get a link to the YML file which you should upload to Yandex Market.

== Frequently Asked Questions ==

= Руский =

= Какие типы валют поддерживаются плагином? =

Данные о ценах принимаются в рублях (RUR, RUB), гривнах (UAH), долларах (USD) и евро (EUR). На данный момент в WooCommerce не реализована поддержка белорусских рублей (BYR) и тенге (KZT), так что плагин их тоже не поддерживает. На Яндекс Маркете цены могут отображаться в рублях, гривнах, белорусских рублях и тенге в зависимости от региона пользователя.

В качестве основной валюты (для которой установлено rate="1") могут быть использованы только рубль (RUR, RUB) и гривна (UAH). Если в WooCommerce установлены доллары (USD) или евро (EUR), то используется курс Центрального Банка той страны, которая указана в настройках магазина на Яндекс Маркет. Применяется курс, установленный на текущий день. Курс обновляется ежедневно в 00.00.

= Как поменять настойки плагина? =

Настройки плагина можно осуществить на вкладке 'Товары' в менюю 'WooCommerce' - 'Настройки'.

В настоящий момент поддерживаются следующие настройки:
- Изменение названия магазина;
- Измнение названия компании;
- Изменение количества изображений при экспорте товарных предложений;
- Использование произвольного поля для элемента vendor;
- Использование произвольного поля для элемента market_category;
- Поддержка элемента sales_notes;
- Поддержка товаров со статусом предзаказ.

= Какие требования к WordPress, WooCommerce и оборудованию? =

Плагин был протестирован на последних версиях WordPress, но, скорее всего, он будет работать и на более старых версиях.

WooCommerce также тестировался на последних версиях.

Версия PHP должна быть не ниже 5.4. Полная поддержка версии 7.0.

= English =

= What currencies are supported? =

Yandex Market support six types of currency: Russian Ruble (RUB), Ukrainian Hryvnia (UAH), Belarusian Ruble (BYR), Kazakhstani Tenge (KZT), US Dollar (USD) and Euro (EUR). But WooCommerce doesn't support Belarusian Ruble (BYR) and Kazakhstani Tenge (KZT). So the plugin checks what currency you are using. If it's Russian Ruble (RUB) or Ukrainian Hryvnia (UAH), then the products are exported under that currency. If you are using US Dollar (USD) or Euro (EUR), then the products are exporter under that currency *but* Yandex Market will list all the products in Russian Ruble (RUB) making a USD-RUB or EUR-RUB conversion using the bank exchange rate of the country of the shop. Country is selected in the partner interface of Yandex Market.

= Does this plugin work with newest WP version and also older versions? =

The plugin should work with any version of WordPress, because it mainly takes data from WooCommerce. The latest version of the plugin has been tested with the latest version of WooCommerce.

= What product fields does the plugin support? =

For now it is possible to export using basic Yandex offer format. The next product fields will be exported: title, description, price, url, category and picture.

= What themes can I use with this plugin? =

Market Explorer is theme independent. You can use it with any theme you want.

= Will other Yandex formats be supported? =

Yes.

== Screenshots ==

1. Screenshot of the plugin main page.
2. Screenshot of the settings page.

== Changelog ==

= 0.3.1 =
* FIXED: Официальный релиз 0.3.*

= 0.3.0 =
* NEW: Добавлена фильтрация выгрузки по категориям.
* FIXED: Исправлены ошибки с невозможностью экспорта импортированных товаров.
* FIXED: В названии товаров заменяются запрещенные символы на соответствующие коды.
* CHANGED: Настройки и генерация файла объеденены под одним пунктом меню. Теперь вся информация о плагине доступна в разделе WooCommerce - Market Exporter.
* CHANGED: Стили интерфейса придевены в соответствие общему стилю WordPress.

= 0.2.7 =
* FIXED: Исправлена работа Cron. Файлы, как и задумано, должны генерироваться раз в сутки.

= 0.2.6 =
* FIXED: Исправлены некоторые ошибки при работы с вариативными товарами. Остается ошибка с товарами, где вариации строятся по нескольким атрибутам.
* FIXED: Исправлены неточности в переводе.
* FIXED: Исправлена ссылка при генерации файла.

= 0.2.4 =
* FIXED: Исправлена ошибка при активации плагина на PHP 5.3.
* FIXED: Исправлены ошибки. Оптимизация кода. Обновлены переводы.

= 0.2.3 =
* CHANGED: Code optimization.

= 0.2.2 =
* NEW: Added support for cron. Now file will be automatically generated daily.
* CHANGED: Added CDATA to description.
* FIXED: Couldn't export on multisite installations.
* FIXED: Images didn't export correctly or sometimes didn't export at all.

= 0.2.0 =
* NEW: Added support for variable products.

= 0.1.0 =
* NEW: Added a list of generated files to the plugin main page. Files can be viewed or deleted.
* NEW: Added new option to enable or disable date at the end of the file name.
* NEW: Added support for products on backorder.
* FIXED: Issues with HTML tags and unsupported characters in description field.

= 0.0.7 =
* NEW: Now it's possible to select a custom attribute to be used as a 'vendor' property.
* NEW: Added support for custom element 'market_category'.
* NEW: Added support for custom element 'sales_notes'.
* CHANGED: Product field 'description' (previously 'short description') is now used as value in 'description' element.
* CHANGED: If 'discount price' is set for a product, old price will be exported to 'oldprice' element.
* CHANGED: Added current Russian translations.
* FIXED: Product 'description' element will not be set if product 'description' field is left blank.

= 0.0.6 =
* NEW: Added new option - 'Number of images'. Specify how many images to export per product.
* CHANGED: Code cleanup and optimization.
* FIXED: Image export... Again.

= 0.0.5 =
* NEW: Added support for the following currencies: RUB, UAH, USD and EUR.
* CHANGED: Export up to 10 product images.
* CHANGED: Use arrays for storing plugin options in DB instead of single values. Better for performance in the long run.
* CHANGED: Items out of stock will not be exported.
* CHANGED: Moved settings page to WooCommerce settings page under Products tab.
* FIXED: Image export.

= 0.0.4 =
* NEW: Flat rate shipping support. Plugin first checks if local delivery is enabled. If not - get the price of flat rate shipping.
* NEW: NAME and COMPANY fields are now customizable.
* FIXED: Remove all HTML tags on all text fields in YML file.

= 0.0.3 =
* FIXED: Bugfixes.

= 0.0.2 =
* NEW: YML generation: products with status 'hidden' are not exported.
* NEW: YML generation: use SKU field as vendorCode.
* CHANGED: Optimized run_plugin()
* CHANGED: Export YML to market-exporter/ directory in uploads/ (previously was the YYYY/mm directory), so we don't get a lot of YML files after a period of time.
* FIXED: Language translation.

= 0.0.1 =
* Initial release.

== Upgrade Notice ==

= 0.3.0 =
Добавлена фильтрация по категориям. Переделан механизм экспорта. Более детально на (https://wordpress.org/plugins/market-exporter/changelog/).

= 0.2.2 =
Added daily cron task. Bugfixes.

= 0.2.0 =
Added support for variable products.

= 0.1.0 =
End of the year release. For a full list of changes refer to (https://wordpress.org/plugins/market-exporter/changelog/).

= 0.0.7 =
For a full list of changes refer to (https://wordpress.org/plugins/market-exporter/changelog/).

= 0.0.6 =
Bug fixes. New image options on settings page.

= 0.0.5 =
Now supports RUB, UAH, USD and EUR currencies. Export up to 10 product images. Items out of stock are not exported anymore. Fixed various bugs.

= 0.0.4 =
Fixed delivery price issues. Added support for flat rate shipping method. NAME and COMPANY fields now customizable.

= 0.0.3 =
Fixed various bugs.

= 0.0.2 =
Utilize SKU field as vendorCode in YML file. Hidden products no longer export. Full changelog can be found at (https://wordpress.org/plugins/market-exporter/changelog/).

= 0.0.1 =
Initial release of the plugin. Basic Yandex offer support.
