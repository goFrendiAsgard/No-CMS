        $this->add_navigation("{{ front_navigation_name }}", "Browse {{ navigation_caption }}", 
            $module_path."/front/{{ navigation_name }}", $this->PRIV_EVERYONE, "{{ navigation_parent_name }}"
        );