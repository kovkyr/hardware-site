# hardware-site

Hardware site

* Default user: admin
* Default password: 123456

## How to deploy server in Docker container

* Update postgresql database name, user and password in `.env` file:
```
DB_NAME=app
DB_USER=app
DB_PASSWORD=123456
```

* Run docker services:
```
docker-compose up -d
```

* Stop docker services:
```
docker-compose down
```

Go to http://localhost:3000/

## How to deploy server in Ubuntu 22.04

* Change `ansible_user` in `deploy/hosts.yml` file to user with sudo permissions:
```
ansible_user: su
```

* Update db name, user and password in `deploy/main.yml` file:
```
dbname: app
dbuser: app
dbpassword: 123456
```

* Update db name, user and password in `src/php/api/database-manager.php` file (should be the same as in `deploy/main.yml` file):
```
self::$instance = new PDO('pgsql:host=localhost;port=5432;dbname=app;user=app;password=123456');
```


* Go to deploy directory and run commands:
```
sudo apt-add-repository ppa:ansible/ansible
sudo apt-get update
sudo apt-get install -y ansible
sudo ansible-playbook -i hosts.yml main.yml
```

Go to http://localhost/

## How to deploy server in Vagrant

* Prepare server:
    * Install VirtualBox
    * Install Vagrant

* Update db name, user and password in `deploy/main.yml` file:
```
dbname: app
dbuser: app
dbpassword: 123456
```

* Update db name, user and password in `src/php/api/database-manager.php` file (should be the same as in `deploy/main.yml` file):
```
self::$instance = new PDO('pgsql:host=localhost;port=5432;dbname=app;user=app;password=123456');
```

* Create/start server:
```
vagrant up
```

* Stop server:
```
vagrant halt
```

* Destroy server:
```
vagrant destroy
```

* Connect to existing server via ssh:
```
vagrant ssh
```

* Disconect from ssh session:
```
Ctrl + D
```

* Change working directory:
```
cd /vagrant
```

Go to http://localhost:3000/

