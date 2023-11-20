# Reading-Marathon
Webプログラミング最終課題  

全ユーザで読んだ本の合計ページ数を競うサービス  

## データベースの準備
1. ##### データベース作成  

```
CREATE DATABASE reading_marathon CHARACTER SET utf8;
```

2. ##### 使用データベース選択

```
USE reading_marathon;
```

3. ##### PageDataテーブル作成

```
CREATE TABLE PageData (ID INT NOT NULL AUTO_INCREMENT, TIME DATE NOT NULL,UserID INT NOT NULL, PAGE INT NOT NULL, NAME TEXT , PRIMARY KEY(ID)) CHARACTER SET utf8;
```
  
4. ##### UserDataテーブル作成

```
CREATE TABLE UserData (UserID INT NOT NULL AUTO_INCREMENT, NAME VARCHAR(26) NOT NULL,PassWord TEXT NOT NULL, Admin INT DEFAULT 0, PRIMARY KEY(UserID)) CHARACTER SET utf8;
```
  
5. ##### 管理者の追加

```
INSERT INTO UserData SET NAME = "admin", PassWord = "admin", Admin = 1;
```
## 操作説明
1. ##### 新規登録画面  
   ユーザ名部分には任意の 8 文字以内のユーザ名を、パスワード部分には任意の文字列を設定する。  
   ※ ログイン時にIDが必要になるのでマイページでIDを確認しておく。
   
2. ##### メイン画面
   プレイヤーが最大4人表示される。  
   上から1位、2位、3位、ユーザの順で表示される。
   更新ボタンを押すと最新のランキングに更新される。  
   
3. ##### マイページ画面
   ユーザ情報の確認、ページデータの登録・変更・削除ができる。
   
4. ##### ユーザ一覧画面
   管理者でログインすると開くことができる。
   全ユーザが一覧表示されており、ユーザの削除ができる。
   
