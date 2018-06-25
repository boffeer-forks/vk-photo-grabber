

## Список консольных команд

* Добавить пользователя в очередь для обработки
  ```bash
  bin/console.php queue:enqueue-user <vk_user_id>
  ```

* Добавить в очередь пользователей из CSV-файла
  ```bash
  bin/console.php queue:batch-enqueue-user <users_csv_file>
  ```

* Вывести список альбомов пользователя
  ```bash
  bin/console.php album:list <vk_user_id>
  ```

* Вывести спикок фотографий из альбома
  ```bash
  console.php photo:list <vk_album_id>
  ```

* Вывести список пользователей
  ```bash
  bin/console.php user:list
  ```

## TODO
* Написать тесты
* Добавть логирование