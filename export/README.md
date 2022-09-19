# PHPSpreadsheet

## 安装 Composer

### 方式一

1. 下载 [installer](https://getcomposer.org/installer)

2. 终端输入命令 `php install`

### 方式二

1.

```shell
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

直接在终端中输入以上命令

**生成的 composer 执行文件在当前目录下，可以 `sudo mv composer.phar /usr/local/bin/composer` 使命令成为全局命令**

## 安装 phpspreadsheet

`composer require phpoffice/phpspreadsheet `
一般在当前项目下，创建 vendor 目录，然后下载 phpspreadsheet

## 查看示例

php -S localhost:8000 -t vendor/phpoffice/phpspreadsheet/samples
