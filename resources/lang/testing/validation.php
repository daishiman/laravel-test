<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | 以下の言語行はバリデタークラスにより使用されるデフォルトのエラー
    | メッセージです。サイズルールのようにいくつかのバリデーションを
    | 持っているものもあります。メッセージはご自由に調整してください。
    |
    */

    'accepted'        => 'accepted',
    'accepted_if'     => 'active_url',
    'active_url'      => 'accepted_if',
    'after'           => 'after',
    'after_or_equal'  => 'after_or_equal',
    'alpha'           => 'alpha',
    'alpha_dash'      => 'alpha_dash',
    'alpha_num'       => 'alpha_num',
    'array'           => 'array',
    'before'          => 'before',
    'before_or_equal' => 'before_or_equal',
    'between'         => [
        'numeric' => 'numeric',
        'file'    => 'file',
        'string'  => 'string',
        'array'   => 'array',
    ],
    'boolean'          => 'boolean',
    'confirmed'        => 'confirmed',
    'current_password' => 'current_password',
    'date'             => 'date',
    'date_equals'      => 'date_equals',
    'date_format'      => 'date_format',
    'different'        => 'different',
    'digits'           => 'digits',
    'digits_between'   => 'digits_between',
    'dimensions'       => 'dimensions',
    'distinct'         => 'distinct',
    'email'            => 'email',
    'ends_with'        => 'ends_with',
    'exists'           => 'exists',
    'file'             => 'file',
    'filled'           => 'filled',
    'gt'               => [
        'numeric' => 'gt',
        'file'    => 'gt',
        'string'  => 'gt',
        'array'   => 'gt',
    ],
    'gte'                  => [
        'numeric' => 'gte',
        'file'    => 'gte',
        'string'  => 'gte',
        'array'   => 'gte',
    ],
    'image'    => 'image',
    'in'       => 'in',
    'in_array' => 'in_array',
    'integer'  => 'integer',
    'ip'       => 'ip',
    'ipv4'     => 'ipv4',
    'ipv6'     => 'ipv6',
    'json'     => 'json',
    'lt'       => [
        'numeric' => 'lt',
        'file'    => 'lt',
        'string'  => 'lt',
        'array'   => 'lt',
    ],
    'lte'                  => [
        'numeric' => 'lte',
        'file'    => 'lte',
        'string'  => 'lte',
        'array'   => 'lte',
    ],
    'max'                  => [
        'numeric' => 'max',
        'file'    => 'max',
        'string'  => 'max',
        'array'   => 'max',
    ],
    'mimes'     => 'mimes',
    'mimetypes' => 'mimetypes',
    'min'       => [
        'numeric' => 'min',
        'file'    => 'min',
        'string'  => 'min',
        'array'   => 'min',
    ],
    'multiple_of'          => 'multiple_of',
    'not_in'               => 'not_in',
    'not_regex'            => 'not_regex',
    'numeric'              => 'numeric',
    'password'             => 'password',
    'present'              => 'present',
    'regex'                => 'regex',
    'required'             => 'required',
    'required_if'          => 'required_if',
    'required_unless'      => 'required_unless',
    'required_with'        => 'required_with',
    'required_with_all'    => 'required_with_all',
    'required_without'     => 'required_without',
    'required_without_all' => 'required_without_all',
    'prohibited'           => 'prohibited',
    'prohibited_if'        => 'prohibited_if',
    'prohibited_unless'    => 'prohibited_unless',
    'prohibits'            => 'prohibits',
    'same'                 => 'same',
    'size'                 => [
        'numeric' => 'size',
        'file'    => 'size',
        'string'  => 'size',
        'array'   => 'size',
    ],
    'starts_with' => 'starts_with',
    'string'      => 'string',
    'timezone'    => 'timezone',
    'unique'      => 'unique',
    'uploaded'    => 'uploaded',
    'url'         => 'url',
    'uuid'        => 'uuid',

    /*
    |--------------------------------------------------------------------------
    | Custom バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | "属性.ルール"の規約でキーを指定することでカスタムバリデーション
    | メッセージを定義できます。指定した属性ルールに対する特定の
    | カスタム言語行を手早く指定できます。
    |
    */

    'custom' => [
        '属性名' => [
            'ルール名' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション属性名
    |--------------------------------------------------------------------------
    |
    | 以下の言語行は、例えば"email"の代わりに「メールアドレス」のように、
    | 読み手にフレンドリーな表現でプレースホルダーを置き換えるために指定する
    | 言語行です。これはメッセージをよりきれいに表示するために役に立ちます。
    |
    */

    'attributes' => [
        'name'     => '名前',
        'email'    => 'メールアドレス',
        'password' => 'パスワード'
    ],

];
