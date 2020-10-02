<?php

namespace Modules\UserRoles\Database\Seeders;

use Illuminate\Database\Seeder;

class ValidationRulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('validation_rules')->delete();

        \DB::table('validation_rules')->insert(array(
            0 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b41',
                'rule' => 'accepted',
                'title' => 'accepted',
                'description' => 'The field under validation must be yes, on, 1, or true. This is useful for validating "Terms of Service" acceptance.',
                'rule_group' => 'Radio-Checkbox-Select-Dropdown',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            1 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b42',
                'rule' => 'active_url',
                'title' => 'active_url',
                'description' => 'The field under validation must have a valid A or AAAA record according to the dns_get_record PHP function. The hostname of the provided URL is extracted using the parse_url PHP function before being passed to dns_get_record.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            2 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b43',
                'rule' => 'after:date',
                'title' => 'after:date',
                'description' => 'The field under validation must be a value after a given date. The dates will be passed into the strtotime PHP function:',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            3 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b44',
                'rule' => 'after_or_equal:date',
                'title' => 'after_or_equal:date',
                'description' => 'The field under validation must be a value after or equal to the given date. For more information, see the after rule.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            4 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b45',
                'rule' => 'alpha',
                'title' => 'alpha',
                'description' => 'The field under validation must be entirely alphabetic characters.',
                'rule_group' => 'Text-Textarea-Email',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            5 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b46',
                'rule' => 'alpha_dash',
                'title' => 'alpha_dash',
                'description' => 'The field under validation may have alpha-numeric characters, as well as dashes and underscores.',
                'rule_group' => 'Text-Textarea-Email',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            6 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b47',
                'rule' => 'alpha_num',
                'title' => 'alpha_num',
                'description' => 'The field under validation must be entirely alpha-numeric characters.',
                'rule_group' => 'Text-Textarea-Email',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            7 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b48',
                'rule' => 'array',
                'title' => 'array',
                'description' => 'The field under validation must be a PHP array.',
                'rule_group' => 'Radio-Checkbox-Select-Dropdown',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            8 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b49',
                'rule' => 'bail',
                'title' => 'bail',
                'description' => 'Stop running validation rules after the first validation failure.',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            9 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b410',
                'rule' => 'before:date',
                'title' => 'before:date',
                'description' => 'The field under validation must be a value preceding the given date. The dates will be passed into the PHP strtotime function. In addition, like the after rule, the name of another field under validation may be supplied as the value of date.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            10 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b411',
                'rule' => 'before_or_equal:date',
                'title' => 'before_or_equal:date',
                'description' => 'The field under validation must be a value preceding or equal to the given date. The dates will be passed into the PHP strtotime function. In addition, like the after rule, the name of another field under validation may be supplied as the value of date.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            11 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b412',
                'rule' => 'between:min,max',
                'title' => 'between:min,max',
                'description' => 'The field under validation must have a size between the given min and max.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '1',
                'is_related_to_other_field' => '0',
            ),
            12 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b413',
                'rule' => 'boolean',
                'title' => 'boolean',
                'description' => 'The field under validation must be able to be cast as a boolean. Accepted input are true, false, 1, 0, "1", and "0".',
                'rule_group' => 'Radio-Checkbox-Select-Dropdown',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            13 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b414',
                'rule' => 'confirmed',
                'title' => 'confirmed',
                'description' => 'The field under validation must have a matching field of foo_confirmation. For example, if the field under validation is password, a matching password_confirmation field must be present in the input.',
                'rule_group' => NULL,
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            14 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b415',
                'rule' => 'date',
                'title' => 'date',
                'description' => 'The field under validation must be a valid, non-relative date according to the strtotime PHP function.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            15 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b416',
                'rule' => 'date_equals:date',
                'title' => 'date_equals:date',
                'description' => 'The field under validation must be equal to the given date. The dates will be passed into the PHP strtotime function.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            16 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b417',
                'rule' => 'date_format:format',
                'title' => 'date_format:format',
                'description' => 'The field under validation must match the given format. You should use either date or date_format when validating a field, not both. This validation rule supports all formats supported by PHP\'s DateTime class.',
                'rule_group' => 'Date-Time-Month-Day-Year',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            17 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b418',
                'rule' => 'different:field',
                'title' => 'different:field',
                'description' => 'The field under validation must have a different value than field.',
                'rule_group' => NULL,
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            18 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b419',
                'rule' => 'digits:value',
                'title' => 'digits:value',
                'description' => 'The field under validation must be numeric and must have an exact length of value.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            19 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b420',
                'rule' => 'digits_between:min,max',
                'title' => 'digits_between:min,max',
                'description' => 'The field under validation must be numeric and must have a length between the given min and max.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '1',
                'is_related_to_other_field' => '0',
            ),
            20 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b421',
                'rule' => 'dimensions',
                'title' => 'dimensions',
                'description' => 'The file under validation must be an image meeting the dimension constraints as specified by the rule\'s parameters',
                'rule_group' => 'Upload-File',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            21 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b422',
                'rule' => 'distinct',
                'title' => 'distinct',
                'description' => 'When working with arrays, the field under validation must not have any duplicate',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            22 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b423',
                'rule' => 'email',
                'title' => 'email',
                'description' => 'The field under validation must be formatted as an e-mail address.',
                'rule_group' => 'Text-Textarea-Email',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            23 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b424',
                'rule' => 'ends_with:foo,bar,...',
                'title' => 'ends_with:foo,bar,...',
                'description' => 'The field under validation must end with one of the given values.',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            24 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b425',
                'rule' => 'exclude_if:anotherfield,value',
                'title' => 'exclude_if:anotherfield,value',
                'description' => 'The field under validation will be excluded from the request data returned by the validate and validated methods if the anotherfield field is equal to value.',
                'rule_group' => NULL,
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            25 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b426',
                'rule' => 'exclude_unless:anotherfield,value',
                'title' => 'exclude_unless:anotherfield,value',
                'description' => 'The field under validation will be excluded from the request data returned by the validate and validated methods unless anotherfield\'s field is equal to value.',
                'rule_group' => NULL,
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            26 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b427',
                'rule' => 'exists:table,column
',
                'title' => 'exists:table,column
',
                'description' => 'The field under validation must exist on a given database table.',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            27 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b428',
                'rule' => 'file',
                'title' => 'file',
                'description' => 'The field under validation must be a successfully uploaded file.',
                'rule_group' => 'Upload-File',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            28 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b429',
                'rule' => 'filled',
                'title' => 'filled',
                'description' => 'The field under validation must not be empty when it is present.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            29 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b430',
                'rule' => 'gt:field',
                'title' => 'gt:field',
                'description' => 'The field under validation must be greater than the given field. The two fields must be of the same type. Strings, numerics, arrays, and files are evaluated using the same conventions as the size rule.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            30 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b431',
                'rule' => 'gte:field',
                'title' => 'gte:field',
                'description' => 'The field under validation must be greater than or equal to the given field. The two fields must be of the same type. Strings, numerics, arrays, and files are evaluated using the same conventions as the size rule.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            31 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b432',
                'rule' => 'image',
                'title' => 'image',
                'description' => 'The file under validation must be an image (jpeg, png, bmp, gif, svg, or webp)',
                'rule_group' => 'Upload-File',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            32 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b433',
                'rule' => 'in:foo,bar,...',
                'title' => 'in:foo,bar,...',
                'description' => 'The field under validation must be included in the given list of values. Since this rule often requires you to implode an array, the Rule::in method may be used to fluently construct the rule',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            33 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b434',
                'rule' => 'in_array:anotherfield.*',
                'title' => 'in_array:anotherfield.*',
                'description' => 'The field under validation must exist in anotherfield\'s values.',
                'rule_group' => NULL,
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            34 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b435',
                'rule' => 'integer',
                'title' => 'integer',
                'description' => 'The field under validation must be an integer.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            35 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b436',
                'rule' => 'lt:field
',
                'title' => 'lt:field
',
                'description' => 'The field under validation must be less than the given field. The two fields must be of the same type. Strings, numerics, arrays, and files are evaluated using the same conventions as the size rule.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            36 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b437',
                'rule' => 'lte:field',
                'title' => 'lte:field',
                'description' => 'The field under validation must be less than or equal to the given field. The two fields must be of the same type. Strings, numerics, arrays, and files are evaluated using the same conventions as the size rule.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            37 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b438',
                'rule' => 'max:value',
                'title' => 'max:value',
                'description' => 'The field under validation must be less than or equal to a maximum value. Strings, numerics, arrays, and files are evaluated in the same fashion as the size rule.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            38 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b439',
                'rule' => 'mimetypes:text/plain,...',
                'title' => 'mimetypes:text/plain,...',
                'description' => 'The file under validation must match one of the given MIME types:',
                'rule_group' => 'Upload-File',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            39 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b440',
                'rule' => 'mimes:foo,bar,...',
                'title' => 'mimes:foo,bar,...',
                'description' => 'The file under validation must have a MIME type corresponding to one of the listed extensions.',
                'rule_group' => 'Upload-File',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            40 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b441',
                'rule' => 'min:value',
                'title' => 'min:value',
                'description' => 'The field under validation must have a minimum value.',
                'rule_group' => 'Number',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            41 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b442',
                'rule' => 'not_in:foo,bar,...',
                'title' => 'not_in:foo,bar,...',
                'description' => 'The field under validation must not be included in the given list of values.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            42 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b443',
                'rule' => 'numeric',
                'title' => 'numeric',
                'description' => 'The field under validation must be numeric.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            43 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b444',
                'rule' => 'required',
                'title' => 'required',
                'description' => 'The field under validation must be present in the input data and not empty.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            44 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b445',
                'rule' => 'required_if:anotherfield,value,...',
                'title' => 'required_if:anotherfield,value,...',
                'description' => 'The field under validation must be present and not empty if the anotherfield field is equal to any value.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            45 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b446',
                'rule' => 'required_unless:anotherfield,value,...',
                'title' => 'required_unless:anotherfield,value,...',
                'description' => 'The field under validation must be present and not empty unless the anotherfield field is equal to any value.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            46 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b447',
                'rule' => 'required_with:foo,bar,...',
                'title' => 'required_with:foo,bar,...',
                'description' => 'The field under validation must be present and not empty only if all of the other specified fields are present.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            47 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b448',
                'rule' => 'required_without:foo,bar,...',
                'title' => 'required_without:foo,bar,...',
                'description' => 'The field under validation must be present and not empty only when any of the other specified fields are not present.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            48 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b449',
                'rule' => 'required_without_all:foo,bar,...',
                'title' => 'required_without_all:foo,bar,...',
                'description' => 'The field under validation must be present and not empty only when all of the other specified fields are not present.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            49 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b450',
                'rule' => 'same:field',
                'title' => 'same:field',
                'description' => 'The given field must match the field under validation.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '1',
            ),
            50 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b451',
                'rule' => 'size:value',
                'title' => 'size:value',
                'description' => 'The field under validation must have a size matching the given value. For string data, value corresponds to the number of characters. For numeric data, value corresponds to a given integer value (the attribute must also have the numeric or integer rule). For an array, size corresponds to the count of the array. For files, size corresponds to the file size in kilobytes.',
                'rule_group' => 'All',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            51 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b452',
                'rule' => 'starts_with:foo,bar,...',
                'title' => 'starts_with:foo,bar,...',
                'description' => 'The field under validation must start with one of the given values.',
                'rule_group' => NULL,
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            52 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b453',
                'rule' => 'string',
                'title' => 'string',
                'description' => 'The field under validation must be a string. If you would like to allow the field to also be null, you should assign the nullable rule to the field.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            53 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b454',
                'rule' => 'unique:table,column,except,idColumn',
                'title' => 'unique:table,column,except,idColumn',
                'description' => 'The field under validation must not exist within the given database table.',
                'rule_group' => 'Text-Textarea-Email',
                'is_active' => '0',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
            54 =>
            array(
                'id' => '3079dd70-9106-4f08-8e77-491ecf16b455',
                'rule' => 'url',
                'title' => 'url',
                'description' => 'The field under validation must be a valid URL.',
                'rule_group' => 'Text-Textarea-Email-Number-Date-Time-Month-Day-Year-URL',
                'is_active' => '1',
                'is_range' => '0',
                'is_related_to_other_field' => '0',
            ),
        ));
    }
}
