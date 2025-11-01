# フリマアプリ

## 環境構築

Dockerビルド

1. git clone 
2. docker-compose up -d build

*MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集してください。

Laravel環境構築

1. docker-compose exec php bash
2. composer install
3. cp .env.example .env  .env.exampleファイルから.envを作成し、環境変数を変更

4. php artisan key:generate
5. php artisan migrate
6. php artisan db:seed


## 使用技術
フロントエンド：HTML/CSS/Blade  
バックエンド：  
・PHP  
・Laravel  
データベース  
・MySQL  
開発環境：Docker/Docker Compose


## URL
・開発環境：
・phpMyAdmin:

## ER図
![ER図](./images/frema.png)







