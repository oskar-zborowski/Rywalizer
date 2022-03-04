<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute musi zostać zaakceptowane.',
    'accepted_if'          => ':attribute musi zostać zaakceptowane gdy :other ma wartość :value.',
    'active_url'           => ':attribute jest nieprawidłowym adresem URL.',
    'after'                => ':attribute musi być datą późniejszą od :date.',
    'after_or_equal'       => ':attribute musi być datą nie wcześniejszą niż :date.',
    'alpha'                => ':attribute może zawierać jedynie litery.',
    'alpha_dash'           => ':attribute może zawierać jedynie litery, cyfry i myślniki.',
    'alpha_num'            => ':attribute może zawierać jedynie litery i cyfry.',
    'array'                => ':attribute musi być tablicą.',
    'attached'             => 'Ten :attribute jest już dołączony.',
    'before'               => ':attribute musi być datą wcześniejszą od :date.',
    'before_or_equal'      => ':attribute musi być datą nie późniejszą niż :date.',
    'between'              => [
        'array'   => ':attribute musi składać się z :min - :max elementów.',
        'file'    => ':attribute musi zawierać się w granicach :min - :max kilobajtów.',
        'numeric' => ':attribute musi zawierać się w granicach :min - :max.',
        'string'  => ':attribute musi zawierać od :min do :max znaków.'
    ],
    'boolean'              => ':attribute musi mieć wartość logiczną prawda albo fałsz.',
    'confirmed'            => 'Hasła nie są identyczne.',
    'current_password'     => 'Hasło jest nieprawidłowe.',
    'date'                 => ':attribute nie jest prawidłową datą.',
    'date_equals'          => ':attribute musi być datą równą :date.',
    'date_format'          => ':attribute nie jest w formacie :format.',
    'declined'             => ':attribute musi zostać odrzucony.',
    'declined_if'          => ':attribute musi zostać odrzucony, gdy :other ma wartość :value.',
    'different'            => ':attribute oraz :other muszą się różnić.',
    'digits'               => ':attribute musi składać się z :digits cyfr.',
    'digits_between'       => ':attribute musi mieć od :min do :max cyfr.',
    'dimensions'           => ':attribute ma niepoprawne wymiary.',
    'distinct'             => ':attribute ma zduplikowane wartości.',
    'email'                => ':attribute nie jest poprawnym adresem e-mail.',
    'ends_with'            => ':attribute musi kończyć się jedną z następujących wartości: :values.',
    'exists'               => 'Podany :attribute nie istnieje.',
    'file'                 => ':attribute musi być plikiem.',
    'filled'               => ':attribute nie może być puste.',
    'gt'                   => [
        'array'   => ':attribute musi mieć więcej niż :value elementów.',
        'file'    => ':attribute musi być większe niż :value kilobajtów.',
        'numeric' => ':attribute musi być większe niż :value.',
        'string'  => ':attribute musi być dłuższe niż :value znaków.'
    ],
    'gte'                  => [
        'array'   => ':attribute musi mieć :value lub więcej elementów.',
        'file'    => ':attribute musi być większe lub równe :value kilobajtów.',
        'numeric' => ':attribute musi być większe lub równe :value.',
        'string'  => ':attribute musi być dłuższe lub równe :value znaków.'
    ],
    'image'                => ':attribute musi być obrazkiem.',
    'in'                   => 'Zaznaczony element :attribute jest nieprawidłowy.',
    'in_array'             => ':attribute nie znajduje się w :other.',
    'integer'              => ':attribute musi być liczbą całkowitą.',
    'ip'                   => ':attribute musi być prawidłowym adresem IP.',
    'ipv4'                 => ':attribute musi być prawidłowym adresem IPv4.',
    'ipv6'                 => ':attribute musi być prawidłowym adresem IPv6.',
    'json'                 => ':attribute musi być poprawnym ciągiem znaków JSON.',
    'lt'                   => [
        'array'   => ':attribute musi mieć mniej niż :value elementów.',
        'file'    => ':attribute musi być mniejsze niż :value kilobajtów.',
        'numeric' => ':attribute musi być mniejsze niż :value.',
        'string'  => ':attribute musi być krótsze niż :value znaków.'
    ],
    'lte'                  => [
        'array'   => ':attribute musi mieć :value lub mniej elementów.',
        'file'    => ':attribute musi być mniejsze lub równe :value kilobajtów.',
        'numeric' => ':attribute musi być mniejsze lub równe :value.',
        'string'  => ':attribute musi być krótsze lub równe :value znaków.'
    ],
    'max'                  => [
        'array'   => ':attribute nie może mieć więcej niż :max elementów.',
        'file'    => ':attribute nie może być większe niż :max kilobajtów.',
        'numeric' => ':attribute nie może być większe niż :max.',
        'string'  => ':attribute nie może być dłuższe niż :max znaków.'
    ],
    'mimes'                => ':attribute musi być plikiem typu :values.',
    'mimetypes'            => ':attribute musi być plikiem typu :values.',
    'min'                  => [
        'array'   => ':attribute musi mieć przynajmniej :min elementów.',
        'file'    => ':attribute musi mieć przynajmniej :min kilobajtów.',
        'numeric' => ':attribute musi być nie mniejsze od :min.',
        'string'  => ':attribute musi mieć przynajmniej :min znaków.'
    ],
    'multiple_of'          => ':attribute musi być wielokrotnością wartości :value',
    'not_in'               => 'Zaznaczony :attribute jest nieprawidłowy.',
    'not_regex'            => 'Format pola :attribute jest nieprawidłowy.',
    'numeric'              => ':attribute musi być liczbą.',
    'password'             => 'Hasło jest nieprawidłowe.',
    'present'              => 'Pole :attribute musi być obecne.',
    'prohibited'           => 'Pole :attribute jest zabronione.',
    'prohibited_if'        => 'Pole :attribute jest zabronione, gdy :other to :value.',
    'prohibited_unless'    => 'Pole :attribute jest zabronione, chyba że :other jest w :values.',
    'prohibits'            => 'Pole :attribute zabrania obecności :other.',
    'regex'                => 'Format pola :attribute jest nieprawidłowy.',
    'relatable'            => 'Ten :attribute może nie być powiązany z tym zasobem.',
    'required'             => 'Pole :attribute jest wymagane.',
    'required_if'          => 'Pole :attribute jest wymagane gdy :other ma wartość :value.',
    'required_unless'      => 'Pole :attribute jest wymagane jeżeli :other nie znajduje się w :values.',
    'required_with'        => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_with_all'    => 'Pole :attribute jest wymagane gdy wszystkie :values są obecne.',
    'required_without'     => 'Pole :attribute jest wymagane gdy :values nie jest obecny.',
    'required_without_all' => 'Pole :attribute jest wymagane gdy żadne z :values nie są obecne.',
    'same'                 => 'Pole :attribute i :other muszą być takie same.',
    'size'                 => [
        'array'   => 'Pole :attribute musi zawierać :size elementów.',
        'file'    => 'Pole :attribute musi mieć :size kilobajtów.',
        'numeric' => 'Pole :attribute musi mieć :size.',
        'string'  => ':attribute musi składać się z :size znaków.'
    ],
    'starts_with'          => ':attribute musi zaczynać się jedną z następujących wartości: :values.',
    'string'               => ':attribute musi być ciągiem znaków.',
    'timezone'             => ':attribute musi być prawidłową strefą czasową.',
    'unique'               => 'Taki :attribute jest już w użyciu.',
    'uploaded'             => 'Nie udało się wgrać pliku :attribute.',
    'url'                  => ':attribute nie jest poprawnym adresem URL.',
    'uuid'                 => ':attribute musi być poprawnym identyfikatorem UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message'
        ],
        'invalid-provider-id' => 'Dostawca OAuth zwrócił nieprawidłową wartość id.',
        'is-missing' => 'Uzupełnij :attribute.'
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'first_name' => 'Imię',
        'last_name' => 'Nazwisko',
        'email' => 'Adres email',
        'birth_date' => 'Data urodzenia',
        'accepted_agreements' => 'Zaakceptowane regulaminy',
        'telephone' => 'Numer telefonu',
        'address_coordinates' => 'Lokalizacja',
        'facebook_profile' => 'Link do profilu na Facebooku',
        'instagram_profile' => 'Link do profilu na Instagramie',
        'website' => 'Link do strony internetowej',
        'facility_id' => 'Identyfikator obiektu sportowego',
        'facility_name' => 'Nazwa obiektu sportowego',
        'facility_street' => 'Adres obiektu sportowego',
        'facility_address_coordinates' => 'Lokalizacja obiektu sportowego',
        'sport_id' => 'Identyfikator sportu',
        'start_date' => 'Data rozpoczęcia',
        'end_date' => 'Data zakończenia',
        'ticket_price' => 'Cena',
        'game_variant_id' => 'Identyfikator wariantu gry',
        'gender_id' => 'Identyfikator płci',
        'minimum_skill_level_id' => 'Identyfikator minimalnego poziomu umiejętności',
        'description' => 'Opis',
        'is_public' => 'Pole określające czy wydarzenie ma być publiczne',
        'sports_positions' => 'Pozycje sportowe',
        'business_name' => 'Nazwa firmy',
        'contact_email' => 'Adres email',
    ]
];
