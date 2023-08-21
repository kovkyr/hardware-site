# hardware-site

Учетная система для инвентаризации оборудования.

## Вариант 1: Установка сервера разработки (на примере Ubuntu 16.04)

### Подготовка к установке

- Перейти в корневую директорию проекта.
- В файле deploy/group_vars/all задать пароль на базу данных PostgreSQL.
- В файле deploy/roles/webserver/src/php/api/database-manager.php задать такой же пароль как и файле deploy/group_vars/all.

### Установить программы разработчика

- Установить VirtualBox
- Установить Vagrant

### Запуск сервера разработки в виртуальной машине

- Перейти в корневую директорию проекта.
- Выполнить комманду.

```
vagrant up
```

- Первый запуск займет продолжительное время и будут установлены все необходимые для работы пакеты, а также будет выполнена их настройка согласно конфигурации ansible.

### Создать схему базы данных в виртуальной машине

- Перейти в корневую директорию проекта.
- Выполнить комманды (ввести пароль, заданный ранее в deploy/group_vars/all).

```
vagrant ssh
psql -h 127.0.0.1 -U app -d app -a -f /var/www/webserver/sql/create-database-scheme.sql
psql -h 127.0.0.1 -U app -d app -a -f /var/www/webserver/sql/create-database-data.sql
```

### Готово

- Веб-сервер будет запущен на порту 3000.
- База данных будет доступна на порту 3001.
- Учетная запись и пароль по умолчанию от веб интерфейса admin с паролем 123456 (можно поменять после входа).
- Для остановки сервера разработки ввести комманду `vagrant halt`.
- Для повторного запуска остановленного ранее сервера разработки ввести комманду `vagrant up`.
Для удаления сервера разработки ввести комманду `vagrant destroy`.

## Вариант 2: Установка сервера на реальную машину (на примере Ubuntu 16.04)

### Подготовка к установке

- Перейти в корневую директорию проекта.
- В файле deploy/group_vars/all задать пароль на базу данных PostgreSQL.
- В файле deploy/roles/webserver/src/php/api/database-manager.php задать такой же пароль как и файле deploy/group_vars/all.

### Настроить пакеты и установить приложение на сервер

- Перейти в deploy директорию проекта.
- Выполнить комманды.

```
sudo apt-add-repository ppa:ansible/ansible
sudo apt-get update
sudo apt-get install -y ansible
sudo ansible-playbook -i hosts.yml main.yml
```

### Создать схему базы данных

- Выполнить комманды (ввести пароль, заданный ранее в deploy/group_vars/all).

```
psql -h 127.0.0.1 -U app -d app -a -f /var/www/webserver/sql/create-database-scheme.sql
psql -h 127.0.0.1 -U app -d app -a -f /var/www/webserver/sql/create-database-data.sql
```

### Удалить скрипты создания базы данных с сервера

- Выполнить комманду.

```
sudo rm -rf /var/www/webserver/sql
```

### Готово

Веб-сервер будет запущен на порту по умолчанию (порт 80).
База данных будет доступна на порту по умолчанию (порт 5432).
Учетная запись и пароль по умолчанию от веб интерфейса admin с паролем 123456 (можно поменять после входа).

## Примечание

Для установки на удаленный сервер достаточно сконфигураровать файл hosts.yml и запустить установку с машины Linux на которой установлен ansible

```
ansible-playbook -i hosts.yml main.yml
```

Пример файла hosts.yml для удаленной установки:

```
all:
  hosts:
    server:
      ansible_host: remote_pc
      ansible_become_method: sudo
      ansible_become: yes
      ansible_user: remote_user
      ansible_password: remote_user_password
```
