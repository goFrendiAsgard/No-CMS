        $this->add_navigation("{{ navigation_name }}", "{{ navigation_caption }} Data", 
            $module_path."/data/{{ navigation_name }}", $this->PRIV_AUTHORIZED, "{{ navigation_parent_name }}"
        );