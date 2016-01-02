[Up](../tutorial.md)

Hook
====

Hooks Let you override the behavior of No-CMS and modules. Hooks are located at `/modules/module_name/hooks.php`.

Defining hook point
-------------------

To define new hook-point, you can call `$this->cms_call_hook($hook_name, $parameters=array())` in your model/controller.

Whenever `cms_call_hook` called, No-CMS will evaluate all hooks available and return an array which the elements contain return value of every hook.

Defining hook
-------------

Hook is basically a function in `/modules/module_name/hooks.php`. The name of the hook should follow this rule:

`hook_` + `module_name with '.', ' ' and '-' replaced into '_'` + `hook_point`

For example, your hook point is `cms_controller_construct` and your module name is `gofrendi.noCMS.pokemon`, then your function name should be `hook_gofrendi_noCMS_pokemon_cms_controller_construct()`.

Predefined hook point
---------------------

* cms_controller_construct()

    Evaluated every time `CMS_Controller` is initialized.

* cms_login($identity, $password)

    Return either `TRUE` or associative array, if login is allowed. Otherwise, return `FALSE`. The associative array should contains one or more of these keys:

    + `email` : should contains valid email
    + `real_name` : real name
    + `birthdate` : birth date in `yyyy-mm-dd` format
    + `sex` : either `male` or `female`
    + `self_description` : description
    + `profile_picture` : Profile picture's filename. Should be located inside `assets/nocms/profile_picture/`. So, you need to copy or upload the file there.

* cms_after_login($user_id, $post)

    Evaluated every time user login.

* cms_after_register($user_id, $post)

    Evaluated every time user registers

* cms_after_change_profile($user_id, $post)

    Evaluated every time user changes profile

* cms_registration_additional_input()

    Return string contains additional html input.

* cms_change_profile_additional_input()

    Return string contains additional html input.

* cms_validate_change_profile($user_id, $post)

    Return associative array. The associative array should contains these keys:

    + `success` : boolean, whether the validation success or fail
    + `message` : error message or empty

* cms_validate_register($post)

    Return associative array. The associative array should contains these keys:

    + `success` : boolean, whether the validation success or fail
    + `message` : error message or empty 
