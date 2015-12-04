[Up](../tutorial.md)

Translation
===========

There are several way to make additional language translation.

* The first one is by create a translation file in `/assets/nocms/languages/`. For example this is Japanese translation:

```php
    <?php
    // Location: /assets/nocms/languages/japanese.php
    $lang['language_alias'] = 'jp';
    $lang['Home'] = 'ホーム';
    $lang['Language'] = '言語';
    $lang['Third Party Authentication'] = 'Third Party Authentication';
    $lang['Login'] = 'ログイン';
    $lang['Logout'] = 'ログアウト';
    $lang['Forgot Password'] = 'パスワード忘れた';
    $lang['Register'] = '登録';
    $lang['No-CMS User Guide'] = 'No-CMSユーザー案内';
    $lang['Change Profile'] = 'プロファイル変更';
    $lang['CMS Management'] = 'CMS管理';
    $lang['Group Management'] = 'グループ管理';
    $lang['User Management'] = 'ユーザー管理';
    $lang['Privilege Management'] = 'アクセス権管理';
    $lang['Navigation Management'] = 'ナビゲーション管理';
    $lang['Widget Management'] = 'ウィジェット管理';
    $lang['Module Management'] = 'モジュール管理';
    $lang['Change Theme'] = 'テーマ変更';
    $lang['Quick Link Management'] = 'クイックリンク管理';
    $lang['Configuration Management'] = '設定';

    $lang['User Info'] = 'ユーザー情報';
    $lang['Share This Page !!'] = 'このページを共有!!';
    $lang['Donate No-CMS'] = 'No-CMS寄付';

    $lang['Welcome'] = 'いらっしゃいませ';

    $lang['Username already exists'] = 'ユーザー名使用済み';
    $lang['Username is empty'] = 'ユーザー名未使用';

```

* The second one is by create a translation file in `/modules/module_name/assets/languages/`. 

* Last, and probably the most convenient for non-programmer, is by accessing `CMS Management | Language Management`
