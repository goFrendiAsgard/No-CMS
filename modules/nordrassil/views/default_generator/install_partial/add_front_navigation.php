        $this->add_navigation("{{ front_navigation_name }}", "Browse {{ table_caption }}", 
            $module_path."/{{ front_controller_name }}", $this->PRIV_EVERYONE, "{{ navigation_parent_name }}"
        );