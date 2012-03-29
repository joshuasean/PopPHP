Pop PHP Framework
=================

Documentation : Locale
----------------------

ロケールのコンポーネントは、アプリケーションの言語サポートと翻訳機能を提供します。あなたは、単にポップ独自のXML言語ファイルで説明されている形式で、必要な言語の翻訳の独自のXMLファイルを作成し、読み込むことができます。

あなたがいる限りポップ/ロケール/データフォルダーに設立されたXML標準に準拠したもので、自分自身の言語の翻訳ファイルを読み込むことができます。

<pre>
use Pop\Locale\Locale;

// Create a Locale object to translate to French, using your own language file.
$lang = Locale::factory('fr')->loadFile('folder/mylangfile.xml);

// Will output 'Ce champ est obligatoire.'
$lang->_e('This field is required.');

// Will return and output 'Ce champ est obligatoire.'
echo $lang->__('This field is required.');
</pre>

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.