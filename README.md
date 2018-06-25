## Требования
* vagrant
* Oracle VirtualBox
* ansible

## Установка

Скопировать файл с параметрами `provisioning/vars/local.yml.dist` в `provisioning/vars/local.yml` и 
прописать OAuth-токен для github, а также прокси для работы с vk.com, если требуется.

Запустить виртуальную машину
```bash
vagrant up
```

## Список консольных команд

Команды запускаются из директории приложения `/home/vagrant/vk-photo-grabber`.

* Добавить пользователя в очередь для обработки
  ```bash
  bin/console.php queue:enqueue-user <vk_user_id>
  ```

* Добавить в очередь пользователей из CSV-файла
  ```bash
  bin/console.php queue:batch-enqueue-user <users_csv_file>
  ```

* Вывести список пользователей
  ```bash
  bin/console.php user:list
  ```

* Вывести список альбомов пользователя
  ```bash
  bin/console.php album:list <vk_user_id>
  ```

* Вывести спикок фотографий из альбома
  ```bash
  console.php photo:list <vk_album_id>
  ```

## TODO
* Написать тесты
* Добавть логирование